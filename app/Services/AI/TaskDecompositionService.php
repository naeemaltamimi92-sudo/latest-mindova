<?php

namespace App\Services\AI;

use App\Models\Challenge;
use App\Models\ChallengeAnalysis;
use App\Models\Workstream;
use App\Models\Task;
use App\Jobs\MatchVolunteersToTasks;

class TaskDecompositionService extends OpenAIService
{
    protected function getModel(): string
    {
        return config('ai.models.task_generation', 'gpt-4o');
    }

    protected function getRequestType(): string
    {
        return 'task_decomposition';
    }

    /**
     * Decompose a challenge into workstreams and tasks.
     *
     * @param Challenge $challenge
     * @param ChallengeAnalysis $briefAnalysis
     * @param ChallengeAnalysis $complexityAnalysis
     * @return array Decomposition results
     */
    public function decompose(
        Challenge $challenge,
        ChallengeAnalysis $briefAnalysis,
        ChallengeAnalysis $complexityAnalysis
    ): array {
        $prompt = $this->buildPrompt($challenge, $briefAnalysis, $complexityAnalysis);
        $systemPrompt = $this->getSystemPrompt();

        $response = $this->makeRequest(
            prompt: $prompt,
            options: [
                'system_prompt' => $systemPrompt,
                'temperature' => 0.4,
            ],
            relatedType: Challenge::class,
            relatedId: $challenge->id
        );

        // Validate response structure
        $requiredFields = [
            'confidence_score',
            'workstreams',
        ];

        if (!$this->validateResponse($response, $requiredFields)) {
            throw new \Exception('Invalid task decomposition response structure');
        }

        // Validate workstreams array
        if (!is_array($response['workstreams']) || empty($response['workstreams'])) {
            throw new \Exception('Workstreams array is empty or invalid');
        }

        return $response;
    }

    /**
     * Build the decomposition prompt.
     */
    protected function buildPrompt(
        Challenge $challenge,
        ChallengeAnalysis $briefAnalysis,
        ChallengeAnalysis $complexityAnalysis
    ): string {
        $objectives = json_encode($briefAnalysis->objectives);
        $constraints = json_encode($briefAnalysis->constraints);
        $complexityData = json_encode($complexityAnalysis->validation_errors);

        return <<<PROMPT
Break down this challenge into workstreams and actionable tasks for volunteer execution.

Challenge: {$challenge->title}

Refined Brief:
{$challenge->refined_brief}

Objectives:
{$objectives}

Constraints:
{$constraints}

Complexity Level: {$challenge->complexity_level}
Complexity Analysis:
{$complexityData}

Decompose this challenge into workstreams and tasks. Provide your decomposition in JSON format:

{
  "confidence_score": <float 0-100>,
  "workstreams": [
    {
      "title": "<workstream name>",
      "description": "<workstream description>",
      "objectives": ["<objective 1>", "<objective 2>"],
      "dependencies": ["<workstream title it depends on, or null>"],
      "tasks": [
        {
          "title": "<task title>",
          "description": "<detailed task description>",
          "required_skills": ["<skill 1>", "<skill 2>"],
          "required_experience_level": "<Junior|Mid|Expert|Manager>",
          "expected_output": "<what deliverable is expected from this task>",
          "acceptance_criteria": ["<criterion 1>", "<criterion 2>"],
          "estimated_hours": <integer>,
          "complexity_score": <integer 1-10>
        }
      ]
    }
  ],
  "execution_plan": "<high-level plan for how workstreams should be executed>",
  "critical_path": ["<workstream 1>", "<workstream 2>"],
  "validation_notes": "<concerns or recommendations>"
}

Guidelines:
- Create 2-5 workstreams that represent major areas of work
- Each workstream should have 2-8 tasks
- Tasks should be concrete, measurable, and achievable
- Estimated hours should be realistic (typically 4-40 hours per task)
- Complexity score: 1-3 (simple), 4-6 (moderate), 7-10 (complex)
- Required skills should be specific (e.g., "Python Programming", "UI/UX Design", "Market Research")
- Required experience level: Junior (0-2 years), Mid (2-5 years), Expert (5+ years), Manager (leadership role)
- Expected output should describe the deliverable (e.g., "Technical report in PDF format", "Working prototype code")
- Acceptance criteria should be specific, measurable conditions for task completion
- Dependencies should reference other workstream titles
- Critical path should list workstreams in order of execution priority
- Total workstream count should match complexity (Level 3: 2-3 workstreams, Level 4: 4-5 workstreams)
PROMPT;
    }

    /**
     * Get the system prompt.
     */
    protected function getSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert program manager and agile coach specializing in breaking down complex projects into executable work items. Your role is to decompose challenges into logical workstreams and specific tasks that volunteers can execute.

You must:
1. Provide responses in valid JSON format
2. Create workstreams that represent coherent areas of work
3. Break workstreams into specific, actionable tasks
4. Make realistic estimates for effort and complexity
5. Identify skill requirements accurately
6. Consider dependencies between workstreams
7. Create tasks that are neither too large nor too granular

Your decomposition will be used to match volunteers with tasks based on their skills and availability.
SYSTEM;
    }

    /**
     * Store the decomposition results.
     */
    public function storeResults(Challenge $challenge, array $decomposition): void
    {
        // Create challenge analysis record for stage 3
        ChallengeAnalysis::create([
            'challenge_id' => $challenge->id,
            'stage' => 'decomposition',
            'objectives' => null,
            'constraints' => null,
            'success_criteria' => null,
            'confidence_score' => $decomposition['confidence_score'],
            'validation_status' => $this->meetsConfidenceThreshold($decomposition['confidence_score'])
                ? 'passed'
                : 'needs_review',
            'requires_human_review' => !$this->meetsConfidenceThreshold($decomposition['confidence_score']),
            'validation_errors' => isset($decomposition['validation_notes']) && !empty($decomposition['validation_notes'])
                ? ['notes' => $decomposition['validation_notes']]
                : null,
        ]);

        // Create workstreams and tasks
        foreach ($decomposition['workstreams'] as $workstreamData) {
            $workstream = Workstream::create([
                'challenge_id' => $challenge->id,
                'title' => $workstreamData['title'],
                'description' => $workstreamData['description'],
                'objectives' => $workstreamData['objectives'],
                'dependencies' => $workstreamData['dependencies'] ?? null,
                'status' => 'pending',
            ]);

            // Create tasks for this workstream
            foreach ($workstreamData['tasks'] as $taskData) {
                $taskAttributes = [
                    'challenge_id' => $challenge->id,
                    'workstream_id' => $workstream->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'required_skills' => $taskData['required_skills'],
                    'required_experience_level' => $taskData['required_experience_level'] ?? 'Mid',
                    'expected_output' => $taskData['expected_output'] ?? 'Completed task deliverable',
                    'acceptance_criteria' => $taskData['acceptance_criteria'] ?? [],
                    'estimated_hours' => $taskData['estimated_hours'],
                    'complexity_score' => $taskData['complexity_score'] ?? 5,
                    'status' => 'pending',
                ];

                \Log::info('Creating task with attributes', $taskAttributes);
                Task::create($taskAttributes);
            }
        }

        // Update challenge status to active
        $challenge->update(['status' => 'active']);

        // Dispatch volunteer matching job
        MatchVolunteersToTasks::dispatch($challenge);
    }
}
