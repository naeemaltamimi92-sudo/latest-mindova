<?php

namespace App\Services\AI;

use App\Models\WorkSubmission;

class SolutionScoringService extends AnthropicService
{
    protected function getModel(): string
    {
        return config('ai.models.solution_analysis', 'claude-sonnet-4-20250514');
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
            'is_spam',
            'relevance_score',
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

Your task is to evaluate this solution's quality, detect spam/irrelevant content, and determine if it adequately solves the task. Return your analysis in JSON format:

{
  "quality_score": <integer 0-100>,
  "solves_task": <boolean true/false>,
  "is_spam": <boolean true/false>,
  "relevance_score": <integer 0-100>,
  "spam_reason": "<reason if is_spam is true, otherwise null>",
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

SPAM DETECTION CRITERIA:
Set is_spam to TRUE if ANY of these apply:
- Gibberish, random text, or meaningless content
- Copy-pasted unrelated content (e.g., lorem ipsum, random articles)
- Promotional or advertising content
- Extremely low effort (single word, repeated characters, placeholder text)
- Completely off-topic from the task requirements
- Automated/bot-generated nonsense

RELEVANCE SCORE CRITERIA (0-100):
- 0-20: Completely unrelated to task (likely spam)
- 21-40: Marginally related, mostly off-topic
- 41-60: Somewhat related but misses key requirements
- 61-80: Related and addresses most requirements
- 81-100: Highly relevant, directly addresses all task requirements

EVALUATION GUIDELINES:
- Consider the complexity of the task when evaluating
- Assess whether the solution achieves the task's objectives
- Evaluate code quality, documentation, and best practices
- Consider the deliverable URL and attachments if provided
- Provide constructive feedback for improvement
- Be fair but thorough in your assessment
- Flag spam immediately - do not provide detailed feedback for spam
- If is_spam is true, set quality_score to 0 and solves_task to false
PROMPT;
    }

    /**
     * Get the system prompt.
     */
    protected function getSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert software architect and code reviewer. Your role is to analyze task solution submissions, detect spam/irrelevant content, and assess quality, completeness, and correctness.

You must:
1. Provide responses in valid JSON format
2. FIRST check if the submission is spam or irrelevant - flag immediately if so
3. Evaluate solutions objectively based on requirements
4. Consider both functionality and code quality
5. Provide constructive, actionable feedback
6. Be fair but maintain high standards
7. Identify both strengths and areas for improvement
8. Consider the complexity level of the task
9. Assess whether the solution truly solves the problem
10. Assign relevance_score based on how well the submission addresses the task

Your analysis helps ensure quality work, filter out spam, and provides valuable feedback to volunteers.
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
            'is_spam' => $analysis['is_spam'],
            'relevance_score' => $analysis['relevance_score'],
            'ai_feedback' => $analysis['feedback'],
            'ai_analysis_status' => 'completed',
            'ai_analyzed_at' => now(),
            'status' => $analysis['is_spam'] ? 'rejected' : ($analysis['solves_task'] ? 'approved' : 'revision_requested'),
        ]);
    }
}
