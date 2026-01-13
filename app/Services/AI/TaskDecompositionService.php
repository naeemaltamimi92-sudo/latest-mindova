<?php

namespace App\Services\AI;

use App\Models\Challenge;
use App\Models\ChallengeAnalysis;
use App\Models\Workstream;
use App\Models\Task;
use App\Jobs\MatchVolunteersToTasks;

class TaskDecompositionService extends AnthropicService
{
    protected function getModel(): string
    {
        return config('ai.models.task_generation', 'claude-sonnet-4-20250514');
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

        // Comprehensive schema validation
        $this->validateDecompositionSchema($response);

        return $response;
    }

    /**
     * Validate the decomposition schema comprehensively.
     */
    protected function validateDecompositionSchema(array $decomposition): void
    {
        $workstreamCount = count($decomposition['workstreams']);

        // Validate workstream count (2-5)
        if ($workstreamCount < 2 || $workstreamCount > 5) {
            throw new \Exception("Invalid workstream count: {$workstreamCount}. Expected 2-5 workstreams.");
        }

        $validExperienceLevels = ['Junior', 'Mid', 'Expert', 'Manager'];

        foreach ($decomposition['workstreams'] as $wsIndex => $workstream) {
            // Validate workstream required fields
            if (empty($workstream['title']) || empty($workstream['description'])) {
                throw new \Exception("Workstream {$wsIndex} is missing required fields (title, description)");
            }

            if (!isset($workstream['tasks']) || !is_array($workstream['tasks'])) {
                throw new \Exception("Workstream '{$workstream['title']}' has no tasks array");
            }

            $taskCount = count($workstream['tasks']);

            // Validate task count per workstream (2-8)
            if ($taskCount < 2 || $taskCount > 8) {
                throw new \Exception("Workstream '{$workstream['title']}' has {$taskCount} tasks. Expected 2-8 tasks.");
            }

            foreach ($workstream['tasks'] as $taskIndex => $task) {
                // Validate required task fields
                $requiredTaskFields = ['title', 'description', 'required_skills', 'estimated_hours'];
                foreach ($requiredTaskFields as $field) {
                    if (!isset($task[$field])) {
                        throw new \Exception("Task {$taskIndex} in '{$workstream['title']}' is missing required field: {$field}");
                    }
                }

                // Validate estimated_hours range (4-40)
                $hours = $task['estimated_hours'];
                if (!is_numeric($hours) || $hours < 4 || $hours > 40) {
                    throw new \Exception("Task '{$task['title']}' has invalid estimated_hours: {$hours}. Expected 4-40.");
                }

                // Validate complexity_score range (1-10) if provided
                if (isset($task['complexity_score'])) {
                    $complexity = $task['complexity_score'];
                    if (!is_numeric($complexity) || $complexity < 1 || $complexity > 10) {
                        throw new \Exception("Task '{$task['title']}' has invalid complexity_score: {$complexity}. Expected 1-10.");
                    }
                }

                // Validate experience level if provided
                if (isset($task['required_experience_level'])) {
                    if (!in_array($task['required_experience_level'], $validExperienceLevels)) {
                        throw new \Exception("Task '{$task['title']}' has invalid experience level: {$task['required_experience_level']}. Expected: " . implode(', ', $validExperienceLevels));
                    }
                }
            }
        }
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
                // Require all critical fields - no dangerous defaults
                if (!isset($taskData['required_experience_level'])) {
                    throw new \Exception("Task '{$taskData['title']}' is missing required_experience_level");
                }
                if (!isset($taskData['complexity_score'])) {
                    throw new \Exception("Task '{$taskData['title']}' is missing complexity_score");
                }

                $taskAttributes = [
                    'challenge_id' => $challenge->id,
                    'workstream_id' => $workstream->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'required_skills' => $taskData['required_skills'],
                    'required_experience_level' => $taskData['required_experience_level'],
                    'expected_output' => $taskData['expected_output'] ?? 'Completed task deliverable',
                    'acceptance_criteria' => $taskData['acceptance_criteria'] ?? [],
                    'estimated_hours' => $taskData['estimated_hours'],
                    'complexity_score' => $taskData['complexity_score'],
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
