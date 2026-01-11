# OpenAI Integration Strategy - Mindova Platform

## Overview
This document defines how the platform integrates with OpenAI for all cognitive operations while maintaining control, accountability, and cost efficiency.

---

## Core Principles

1. **Structured Outputs:** All OpenAI responses use JSON mode with strict schemas
2. **Confidence Scoring:** Every AI operation returns a confidence score
3. **Audit Trail:** All requests/responses logged in `openai_requests` table
4. **Cost Control:** Appropriate model selection, prompt optimization, caching
5. **Error Handling:** Graceful fallbacks, retries, human review triggers
6. **Validation:** Platform validates all AI outputs before execution

---

## OpenAI Service Base Class

```php
namespace App\Services\AI;

use App\Models\OpenAIRequest;
use OpenAI;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(config('ai.openai.api_key'));
    }

    protected function chat(
        string $requestType,
        string $prompt,
        string $model = null,
        array $responseFormat = null,
        $relatedEntity = null
    ): array {
        $model = $model ?? config("ai.models.{$requestType}");
        $startTime = microtime(true);

        try {
            $params = [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->getSystemPrompt($requestType)
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
            ];

            // Use JSON mode if schema provided
            if ($responseFormat) {
                $params['response_format'] = [
                    'type' => 'json_schema',
                    'json_schema' => [
                        'name' => $requestType,
                        'strict' => true,
                        'schema' => $responseFormat
                    ]
                ];
            }

            $response = $this->client->chat()->create($params);

            $duration = (microtime(true) - $startTime) * 1000;

            // Parse response
            $content = $response->choices[0]->message->content;
            $data = json_decode($content, true);

            // Log the request
            $this->logRequest(
                requestType: $requestType,
                model: $model,
                prompt: $prompt,
                response: $content,
                tokensPrompt: $response->usage->promptTokens,
                tokensCompletion: $response->usage->completionTokens,
                tokensTotal: $response->usage->totalTokens,
                duration: $duration,
                status: 'success',
                relatedEntity: $relatedEntity
            );

            return $data;

        } catch (\Exception $e) {
            $duration = (microtime(true) - $startTime) * 1000;

            $this->logRequest(
                requestType: $requestType,
                model: $model,
                prompt: $prompt,
                response: null,
                tokensPrompt: 0,
                tokensCompletion: 0,
                tokensTotal: 0,
                duration: $duration,
                status: 'failed',
                relatedEntity: $relatedEntity,
                error: $e->getMessage()
            );

            throw $e;
        }
    }

    protected function logRequest(
        string $requestType,
        string $model,
        string $prompt,
        ?string $response,
        int $tokensPrompt,
        int $tokensCompletion,
        int $tokensTotal,
        float $duration,
        string $status,
        $relatedEntity = null,
        ?string $error = null
    ): void {
        OpenAIRequest::create([
            'request_type' => $requestType,
            'model' => $model,
            'prompt' => $prompt,
            'response' => $response,
            'tokens_prompt' => $tokensPrompt,
            'tokens_completion' => $tokensCompletion,
            'tokens_total' => $tokensTotal,
            'cost_usd' => $this->calculateCost($model, $tokensPrompt, $tokensCompletion),
            'duration_ms' => (int) $duration,
            'status' => $status,
            'error_message' => $error,
            'related_type' => $relatedEntity ? get_class($relatedEntity) : null,
            'related_id' => $relatedEntity?->id,
        ]);
    }

    protected function calculateCost(string $model, int $promptTokens, int $completionTokens): float
    {
        $pricing = config('ai.pricing');

        $inputCost = ($promptTokens / 1000000) * $pricing[$model]['input'];
        $outputCost = ($completionTokens / 1000000) * $pricing[$model]['output'];

        return $inputCost + $outputCost;
    }

    protected function getSystemPrompt(string $requestType): string
    {
        return match($requestType) {
            'cv_analysis' => 'You are an expert HR analyst specializing in CV/resume analysis across all industries.',
            'challenge_analysis' => 'You are a problem analysis expert who structures ambiguous challenges into clear, actionable plans.',
            'task_generation' => 'You are a project decomposition expert who breaks complex work into clear, executable tasks.',
            'volunteer_matching' => 'You are a talent matching expert who pairs the right people with the right tasks.',
            'idea_scoring' => 'You are an idea evaluation expert who scores proposals on quality, feasibility, and relevance.',
            default => 'You are a helpful AI assistant.'
        };
    }
}
```

---

## 1. CV Analysis Service

### Purpose
Extract skills, experience, education from uploaded CVs.

### Implementation

```php
namespace App\Services\AI;

use App\DTOs\CVAnalysisDTO;
use App\Models\Volunteer;

class CVAnalyzerService extends OpenAIService
{
    public function analyze(Volunteer $volunteer, string $cvContent): CVAnalysisDTO
    {
        $prompt = $this->buildPrompt($cvContent);
        $schema = $this->getResponseSchema();

        $data = $this->chat(
            requestType: 'cv_analysis',
            prompt: $prompt,
            model: config('ai.models.cv_analysis'),
            responseFormat: $schema,
            relatedEntity: $volunteer
        );

        return CVAnalysisDTO::fromArray($data);
    }

    private function buildPrompt(string $cvContent): string
    {
        return <<<PROMPT
Analyze the following CV/resume and extract structured information.

CV Content:
{$cvContent}

Extract the following:
1. Education history (degrees, institutions, years)
2. Work experience (roles, companies, durations, responsibilities)
3. Skills (technical, soft, domain-specific)
4. Professional domains (industries/fields of expertise)
5. Total years of experience (calculated)
6. Experience level (Junior: 0-2 years, Mid: 2-5 years, Expert: 5-10 years, Manager: 10+ years or leadership roles)

Provide a confidence score (0-100) indicating extraction quality and completeness.

Return comprehensive, accurate data even from unstructured CVs.
PROMPT;
    }

    private function getResponseSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'education' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'degree' => ['type' => 'string'],
                            'field' => ['type' => 'string'],
                            'institution' => ['type' => 'string'],
                            'start_year' => ['type' => ['integer', 'null']],
                            'end_year' => ['type' => ['integer', 'null']],
                        ],
                        'required' => ['degree', 'institution'],
                        'additionalProperties' => false
                    ]
                ],
                'work_experience' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'company' => ['type' => 'string'],
                            'start_date' => ['type' => 'string'],
                            'end_date' => ['type' => ['string', 'null']],
                            'duration_years' => ['type' => 'number'],
                            'responsibilities' => [
                                'type' => 'array',
                                'items' => ['type' => 'string']
                            ]
                        ],
                        'required' => ['title', 'company'],
                        'additionalProperties' => false
                    ]
                ],
                'skills' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'name' => ['type' => 'string'],
                            'category' => ['type' => 'string'],
                            'proficiency' => [
                                'type' => 'string',
                                'enum' => ['beginner', 'intermediate', 'advanced', 'expert']
                            ],
                            'years_of_experience' => ['type' => 'number']
                        ],
                        'required' => ['name', 'category', 'proficiency'],
                        'additionalProperties' => false
                    ]
                ],
                'professional_domains' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ],
                'total_years_of_experience' => ['type' => 'number'],
                'experience_level' => [
                    'type' => 'string',
                    'enum' => ['Junior', 'Mid', 'Expert', 'Manager']
                ],
                'bio' => ['type' => 'string'],
                'confidence_score' => [
                    'type' => 'number',
                    'minimum' => 0,
                    'maximum' => 100
                ],
                'reasoning' => ['type' => 'string']
            ],
            'required' => [
                'education',
                'work_experience',
                'skills',
                'professional_domains',
                'total_years_of_experience',
                'experience_level',
                'bio',
                'confidence_score',
                'reasoning'
            ],
            'additionalProperties' => false
        ];
    }
}
```

---

## 2. Challenge Analyzer Service

### Purpose
Analyze submitted challenges through a 3-stage pipeline.

### Implementation

```php
namespace App\Services\AI;

use App\DTOs\ChallengeAnalysisDTO;
use App\Models\Challenge;

class ChallengeAnalyzerService extends OpenAIService
{
    // Stage 1: Transform to structured brief
    public function analyzeBrief(Challenge $challenge): array
    {
        $prompt = <<<PROMPT
A company has submitted the following challenge:

Title: {$challenge->title}
Description: {$challenge->original_description}

Transform this into a structured problem brief:
1. Summarize the core problem clearly
2. Identify all objectives/goals
3. Identify constraints (time, budget, technical, regulatory)
4. List assumptions being made
5. Identify stakeholders
6. Note any critical missing information
7. Define what a successful solution looks like

Provide a confidence score for the completeness of the analysis.
PROMPT;

        return $this->chat(
            requestType: 'challenge_analysis',
            prompt: $prompt,
            responseFormat: $this->getBriefSchema(),
            relatedEntity: $challenge
        );
    }

    // Stage 2: Evaluate complexity
    public function analyzeComplexity(Challenge $challenge, array $briefData): array
    {
        $prompt = <<<PROMPT
Given this problem brief:

Summary: {$briefData['summary']}
Objectives: {$this->formatArray($briefData['objectives'])}
Constraints: {$this->formatArray($briefData['constraints'])}

Evaluate the complexity:
1. Assign a complexity level (1-4):
   - Level 1: Simple, single-domain, suitable for open discussion
   - Level 2: Moderate, may need small team (2-3 people)
   - Level 3: Complex, requires structured team (4-6 people)
   - Level 4: Very complex, large coordinated effort (7+ people)

2. Assess risks (technical, execution, resource)
3. Estimate total effort in hours
4. Provide rationale for the complexity level
5. Provide confidence score
PROMPT;

        return $this->chat(
            requestType: 'challenge_analysis',
            prompt: $prompt,
            responseFormat: $this->getComplexitySchema(),
            relatedEntity: $challenge
        );
    }

    // Stage 3: Decompose into workstreams
    public function decomposeIntoWorkstreams(Challenge $challenge, array $briefData): array
    {
        $prompt = <<<PROMPT
Given this problem:

Summary: {$briefData['summary']}
Objectives: {$this->formatArray($briefData['objectives'])}
Success Criteria: {$this->formatArray($briefData['success_criteria'])}

Break this challenge into parallel workstreams.

A workstream is a track of work that can be done independently or in parallel with others.

For each workstream:
1. Clear title and description
2. What it accomplishes
3. Dependencies on other workstreams (if any)

Return 2-5 workstreams depending on complexity.
PROMPT;

        return $this->chat(
            requestType: 'challenge_analysis',
            prompt: $prompt,
            responseFormat: $this->getWorkstreamSchema(),
            relatedEntity: $challenge
        );
    }

    private function getBriefSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'summary' => ['type' => 'string'],
                'objectives' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ],
                'constraints' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ],
                'assumptions' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ],
                'stakeholders' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ],
                'missing_information' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ],
                'success_criteria' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ],
                'confidence_score' => [
                    'type' => 'number',
                    'minimum' => 0,
                    'maximum' => 100
                ],
                'reasoning' => ['type' => 'string']
            ],
            'required' => ['summary', 'objectives', 'constraints', 'success_criteria', 'confidence_score'],
            'additionalProperties' => false
        ];
    }

    private function getComplexitySchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'complexity_level' => [
                    'type' => 'integer',
                    'minimum' => 1,
                    'maximum' => 4
                ],
                'risk_assessment' => ['type' => 'string'],
                'estimated_effort_hours' => ['type' => 'number'],
                'rationale' => ['type' => 'string'],
                'confidence_score' => [
                    'type' => 'number',
                    'minimum' => 0,
                    'maximum' => 100
                ]
            ],
            'required' => ['complexity_level', 'risk_assessment', 'estimated_effort_hours', 'rationale', 'confidence_score'],
            'additionalProperties' => false
        ];
    }

    private function getWorkstreamSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'workstreams' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                            'dependencies' => [
                                'type' => 'array',
                                'items' => ['type' => 'string']
                            ]
                        ],
                        'required' => ['title', 'description'],
                        'additionalProperties' => false
                    ]
                ],
                'confidence_score' => [
                    'type' => 'number',
                    'minimum' => 0,
                    'maximum' => 100
                ]
            ],
            'required' => ['workstreams', 'confidence_score'],
            'additionalProperties' => false
        ];
    }

    private function formatArray(array $items): string
    {
        return implode("\n- ", array_map(fn($i) => "- $i", $items));
    }
}
```

---

## 3. Task Generator Service

### Purpose
Generate concrete, executable tasks from workstreams.

```php
namespace App\Services\AI;

use App\Models\Workstream;

class TaskGeneratorService extends OpenAIService
{
    public function generateTasks(Workstream $workstream, array $briefData): array
    {
        $prompt = <<<PROMPT
For the following workstream:

Title: {$workstream->title}
Description: {$workstream->description}

Context (from challenge brief):
Summary: {$briefData['summary']}
Success Criteria: {$this->formatArray($briefData['success_criteria'])}

Generate 3-8 concrete, executable tasks.

Each task must include:
1. Clear title
2. Description (what needs to be done)
3. Required skills (specific technical/domain skills)
4. Required experience level (Junior/Mid/Expert/Manager)
5. Expected output (deliverable)
6. Acceptance criteria (how to verify it's done correctly)
7. Estimated hours
8. Priority (low/medium/high/critical)
9. Dependencies (other task titles if any)

Make tasks:
- Specific and actionable
- Properly scoped (not too large or too small)
- Have clear deliverables
- Matchable to volunteers based on skills
PROMPT;

        return $this->chat(
            requestType: 'task_generation',
            prompt: $prompt,
            responseFormat: $this->getTaskSchema(),
            relatedEntity: $workstream
        );
    }

    private function getTaskSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'tasks' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                            'required_skills' => [
                                'type' => 'array',
                                'items' => ['type' => 'string']
                            ],
                            'required_experience_level' => [
                                'type' => 'string',
                                'enum' => ['Junior', 'Mid', 'Expert', 'Manager']
                            ],
                            'expected_output' => ['type' => 'string'],
                            'acceptance_criteria' => [
                                'type' => 'array',
                                'items' => ['type' => 'string']
                            ],
                            'estimated_hours' => ['type' => 'number'],
                            'priority' => [
                                'type' => 'string',
                                'enum' => ['low', 'medium', 'high', 'critical']
                            ],
                            'dependencies' => [
                                'type' => 'array',
                                'items' => ['type' => 'string']
                            ]
                        ],
                        'required' => ['title', 'description', 'required_skills', 'required_experience_level', 'expected_output', 'acceptance_criteria', 'estimated_hours', 'priority'],
                        'additionalProperties' => false
                    ]
                ],
                'confidence_score' => [
                    'type' => 'number',
                    'minimum' => 0,
                    'maximum' => 100
                ]
            ],
            'required' => ['tasks', 'confidence_score'],
            'additionalProperties' => false
        ];
    }
}
```

---

## 4. Volunteer Matcher Service

```php
namespace App\Services\AI;

use App\Models\Task;
use Illuminate\Support\Collection;

class VolunteerMatcherService extends OpenAIService
{
    public function findMatches(Task $task, Collection $availableVolunteers): array
    {
        $taskData = [
            'title' => $task->title,
            'description' => $task->description,
            'required_skills' => $task->required_skills,
            'required_experience_level' => $task->required_experience_level,
            'estimated_hours' => $task->estimated_hours,
        ];

        $volunteersData = $availableVolunteers->map(function($volunteer) {
            return [
                'id' => $volunteer->id,
                'experience_level' => $volunteer->experience_level,
                'years_of_experience' => $volunteer->years_of_experience,
                'availability_hours_per_week' => $volunteer->availability_hours_per_week,
                'reputation_score' => $volunteer->reputation_score,
                'skills' => $volunteer->skills->map(fn($s) => [
                    'name' => $s->skill_name,
                    'proficiency' => $s->proficiency_level,
                    'years' => $s->years_of_experience
                ])->toArray(),
                'total_tasks_completed' => $volunteer->total_tasks_completed,
            ];
        })->toArray();

        $prompt = <<<PROMPT
Match volunteers to this task:

Task:
{$this->formatJson($taskData)}

Available Volunteers:
{$this->formatJson($volunteersData)}

For each suitable volunteer (return top 5):
1. Volunteer ID
2. Match score (0-100)
3. Reasoning (why they're a good match)
4. Skill alignment (how their skills match requirements)
5. Potential concerns (if any)

Rank by match score descending.
PROMPT;

        return $this->chat(
            requestType: 'volunteer_matching',
            prompt: $prompt,
            model: config('ai.models.volunteer_matching'),
            responseFormat: $this->getMatchSchema(),
            relatedEntity: $task
        );
    }

    private function getMatchSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'matches' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'volunteer_id' => ['type' => 'integer'],
                            'match_score' => [
                                'type' => 'number',
                                'minimum' => 0,
                                'maximum' => 100
                            ],
                            'reasoning' => ['type' => 'string'],
                            'skill_alignment' => ['type' => 'string'],
                            'concerns' => ['type' => ['string', 'null']]
                        ],
                        'required' => ['volunteer_id', 'match_score', 'reasoning', 'skill_alignment'],
                        'additionalProperties' => false
                    ]
                ],
                'confidence_score' => [
                    'type' => 'number',
                    'minimum' => 0,
                    'maximum' => 100
                ]
            ],
            'required' => ['matches', 'confidence_score'],
            'additionalProperties' => false
        ];
    }

    private function formatJson($data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
```

---

## 5. Idea Evaluator Service

```php
namespace App\Services\AI;

use App\Models\Idea;
use App\Models\Challenge;

class IdeaEvaluatorService extends OpenAIService
{
    public function scoreIdea(Idea $idea, Challenge $challenge): array
    {
        $prompt = <<<PROMPT
Evaluate this idea for a challenge:

Challenge:
Title: {$challenge->title}
Summary: {$challenge->challengeAnalyses->first()->summary}
Objectives: {$this->formatArray($challenge->challengeAnalyses->first()->objectives)}

Idea:
{$idea->content}

Score the idea on:
1. Quality (0-100): Is it well-thought-out, clear, and detailed?
2. Relevance (0-100): Does it address the challenge objectives?
3. Feasibility (0-100): Can it realistically be implemented?

Provide:
- Scores for each dimension
- Overall score (weighted average)
- Feedback explaining the scores
- Strengths and weaknesses

PROMPT;

        return $this->chat(
            requestType: 'idea_scoring',
            prompt: $prompt,
            model: config('ai.models.idea_scoring'),
            responseFormat: $this->getIdeaScoringSchema(),
            relatedEntity: $idea
        );
    }

    private function getIdeaScoringSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'quality_score' => [
                    'type' => 'number',
                    'minimum' => 0,
                    'maximum' => 100
                ],
                'relevance_score' => [
                    'type' => 'number',
                    'minimum' => 0,
                    'maximum' => 100
                ],
                'feasibility_score' => [
                    'type' => 'number',
                    'minimum' => 0,
                    'maximum' => 100
                ],
                'overall_score' => [
                    'type' => 'number',
                    'minimum' => 0,
                    'maximum' => 100
                ],
                'feedback' => ['type' => 'string'],
                'strengths' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ],
                'weaknesses' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ]
            ],
            'required' => ['quality_score', 'relevance_score', 'feasibility_score', 'overall_score', 'feedback', 'strengths', 'weaknesses'],
            'additionalProperties' => false
        ];
    }
}
```

---

## Configuration

**config/ai.php**

```php
return [
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'timeout' => 60,
    ],

    'models' => [
        'cv_analysis' => 'gpt-4o',
        'challenge_analysis' => 'gpt-4o',
        'task_generation' => 'gpt-4o',
        'volunteer_matching' => 'gpt-4o-mini', // Cheaper for structured matching
        'idea_scoring' => 'gpt-4o-mini',       // Cheaper for scoring
    ],

    'confidence_threshold' => [
        'cv_analysis' => 70.0,
        'challenge_analysis' => 75.0,
        'task_generation' => 80.0,
        'volunteer_matching' => 65.0,
        'idea_scoring' => 60.0,
    ],

    'pricing' => [
        'gpt-4o' => [
            'input' => 2.50,  // per 1M tokens
            'output' => 10.00,
        ],
        'gpt-4o-mini' => [
            'input' => 0.150,
            'output' => 0.600,
        ],
    ],

    'retry' => [
        'max_attempts' => 3,
        'delay_ms' => 1000,
    ],
];
```

---

## Cost Optimization Strategies

1. **Model Selection:**
   - Use `gpt-4o` for complex analysis (CV, challenge, tasks)
   - Use `gpt-4o-mini` for matching and scoring (80% cheaper)

2. **Prompt Optimization:**
   - Concise, focused prompts
   - Avoid unnecessary context
   - Cache common instructions in system prompts

3. **Response Caching:**
   - Cache CV analysis results
   - Don't re-analyze unless CV changes

4. **Batch Processing:**
   - Queue multiple ideas for scoring
   - Process in batches when possible

5. **Rate Limiting:**
   - Control concurrent requests
   - Implement queuing for non-urgent operations

---

## Error Handling

```php
try {
    $result = $cvAnalyzer->analyze($volunteer, $cvContent);

    if ($result->confidenceScore < config('ai.confidence_threshold.cv_analysis')) {
        // Below threshold - flag for human review
        $volunteer->update(['validation_status' => 'needs_review']);
        // Notify admin
    } else {
        // Passed - continue processing
        $volunteer->update(['validation_status' => 'passed']);
    }

} catch (\OpenAI\Exceptions\RateLimitException $e) {
    // Retry later
    AnalyzeCV::dispatch($volunteer)->delay(now()->addMinutes(5));

} catch (\Exception $e) {
    // Log and flag
    Log::error('CV analysis failed', ['volunteer_id' => $volunteer->id, 'error' => $e->getMessage()]);
    $volunteer->update(['ai_analysis_status' => 'failed']);
}
```

---

## Testing Strategy

**Mock OpenAI for tests:**

```php
// tests/Fakes/FakeOpenAIService.php
class FakeOpenAIService extends OpenAIService
{
    public function __construct(
        public array $responses = []
    ) {}

    protected function chat(...$args): array
    {
        return array_shift($this->responses) ?? [];
    }
}

// In tests:
$this->app->instance(OpenAIService::class, new FakeOpenAIService([
    ['confidence_score' => 85, 'skills' => [...]]
]));
```

---

## Next Steps

1. Install OpenAI PHP SDK: `composer require openai-php/client`
2. Implement base OpenAIService class
3. Implement each AI service (CVAnalyzer, ChallengeAnalyzer, etc.)
4. Create DTOs for all responses
5. Implement validation services
6. Create jobs that use AI services
7. Add comprehensive logging
8. Implement cost tracking dashboard
