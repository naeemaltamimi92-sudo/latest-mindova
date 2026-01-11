<?php

namespace App\Services\AI;

use App\Models\WorkSubmission;

class SolutionScoringService extends OpenAIService
{
    protected function getModel(): string
    {
        return config('ai.models.solution_analysis', 'gpt-4o');
    }

    protected function getRequestType(): string
    {
        return 'solution_scoring';
    }

    /**
     * Analyze and score a solution's quality and completeness.
     *
     * @param WorkSubmission $submission
     * @return array Analysis results with quality score and solves_task flag
     */
    public function analyzeSolution(WorkSubmission $submission): array
    {
        $submission->load('task.challenge', 'volunteer.user');

        $prompt = $this->buildPrompt($submission);
        $systemPrompt = $this->getSystemPrompt();

        $response = $this->makeRequest(
            prompt: $prompt,
            options: [
                'system_prompt' => $systemPrompt,
                'temperature' => 0.2,
            ],
            relatedType: WorkSubmission::class,
            relatedId: $submission->id
        );

        // Validate response structure
        $requiredFields = [
            'quality_score',
            'solves_task',
            'feedback',
            'completeness',
            'correctness',
            'code_quality',
        ];

        if (!$this->validateResponse($response, $requiredFields)) {
            throw new \Exception('Invalid solution analysis response structure');
        }

        return $response;
    }

    /**
     * Build the analysis prompt.
     */
    protected function buildPrompt(WorkSubmission $submission): string
    {
        $task = $submission->task;
        $challenge = $task->challenge;
        $volunteer = $submission->volunteer;

        $attachmentsInfo = '';
        if (!empty($submission->attachments)) {
            $attachmentsInfo = "\nAttachments: " . count($submission->attachments) . " file(s) uploaded";
        }

        $deliverableInfo = '';
        if ($submission->deliverable_url) {
            $deliverableInfo = "\nDeliverable URL: {$submission->deliverable_url}";
        }

        return <<<PROMPT
Analyze the following task solution submission and evaluate its quality and completeness.

CHALLENGE CONTEXT:
Title: {$challenge->title}
Field: {$challenge->field}
Description: {$challenge->original_description}

TASK DETAILS:
Title: {$task->title}
Description: {$task->description}
Complexity Score: {$task->complexity_score}/10
Estimated Hours: {$task->estimated_hours}
Required Skills: {$task->required_skills}

SUBMITTED SOLUTION:
Volunteer: {$volunteer->user->name}
Hours Worked: {$submission->hours_worked}
Description: {$submission->description}{$deliverableInfo}{$attachmentsInfo}

Your task is to evaluate this solution's quality and determine if it adequately solves the task. Return your analysis in JSON format:

{
  "quality_score": <integer 0-100>,
  "solves_task": <boolean true/false>,
  "feedback": "<detailed feedback on the solution>",
  "completeness": <float 0-100 indicating how complete the solution is>,
  "correctness": <float 0-100 indicating technical correctness>,
  "code_quality": <float 0-100 indicating code quality, documentation, best practices>,
  "strengths": [
    "<strength 1>",
    "<strength 2>"
  ],
  "weaknesses": [
    "<weakness 1>",
    "<weakness 2>"
  ],
  "suggestions_for_improvement": [
    "<suggestion 1>",
    "<suggestion 2>"
  ],
  "estimated_impact": "<how this solution contributes to solving the overall challenge>"
}

QUALITY SCORE CRITERIA (0-100):
- 0-40: Low quality
  * Does not meet task requirements
  * Incomplete or incorrect implementation
  * Poor code quality or lacks documentation
  * Missing critical functionality

- 41-70: Medium quality
  * Partially meets task requirements
  * Functional but with issues or gaps
  * Acceptable code quality
  * Could use improvements or refinements

- 71-100: High quality
  * Fully meets or exceeds task requirements
  * Complete and correct implementation
  * Good code quality, well-documented
  * Follows best practices
  * Goes above and beyond expectations

SOLVES_TASK CRITERIA:
- Set to TRUE if:
  * Solution addresses all key requirements of the task
  * Implementation is functionally correct
  * Quality score is at least 70 (70% threshold for approval)
  * No critical issues that prevent use

- Set to FALSE if:
  * Solution is incomplete or missing key functionality
  * Contains critical errors or issues
  * Does not address the task requirements
  * Quality score is below 70

EVALUATION GUIDELINES:
- Consider the complexity of the task when evaluating
- Assess whether the solution achieves the task's objectives
- Evaluate code quality, documentation, and best practices
- Consider the deliverable URL and attachments if provided
- Provide constructive feedback for improvement
- Be fair but thorough in your assessment
PROMPT;
    }

    /**
     * Get the system prompt.
     */
    protected function getSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert software architect and code reviewer. Your role is to analyze task solution submissions and assess their quality, completeness, and correctness.

You must:
1. Provide responses in valid JSON format
2. Evaluate solutions objectively based on requirements
3. Consider both functionality and code quality
4. Provide constructive, actionable feedback
5. Be fair but maintain high standards
6. Identify both strengths and areas for improvement
7. Consider the complexity level of the task
8. Assess whether the solution truly solves the problem

Your analysis helps ensure quality work and provides valuable feedback to volunteers.
SYSTEM;
    }

    /**
     * Store the analysis results.
     */
    public function storeResults(WorkSubmission $submission, array $analysis): void
    {
        $submission->update([
            'ai_quality_score' => $analysis['quality_score'],
            'solves_task' => $analysis['solves_task'],
            'ai_feedback' => $analysis['feedback'],
            'ai_analysis_status' => 'completed',
            'ai_analyzed_at' => now(),
            'status' => $analysis['solves_task'] ? 'approved' : 'revision_requested',
        ]);
    }
}
