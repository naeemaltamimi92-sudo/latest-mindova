<?php

namespace App\Services\AI;

use App\Models\Challenge;
use App\Models\ChallengeAnalysis;

class ComplexityEvaluationService extends AnthropicService
{
    protected function getModel(): string
    {
        return config('ai.models.challenge_analysis', 'claude-sonnet-4-20250514');
    }

    protected function getRequestType(): string
    {
        return 'complexity_evaluation';
    }

    /**
     * Evaluate the complexity of a challenge.
     *
     * @param Challenge $challenge
     * @param ChallengeAnalysis $briefAnalysis
     * @return array Complexity evaluation results
     */
    public function evaluate(Challenge $challenge, ChallengeAnalysis $briefAnalysis): array
    {
        $prompt = $this->buildPrompt($challenge, $briefAnalysis);
        $systemPrompt = $this->getSystemPrompt();

        $response = $this->makeRequest(
            prompt: $prompt,
            options: [
                'system_prompt' => $systemPrompt,
                'temperature' => 0.3, // Lower temperature for more consistent scoring
            ],
            relatedType: Challenge::class,
            relatedId: $challenge->id
        );

        // Validate response structure
        $requiredFields = [
            'confidence_score',
            'complexity_level',
            'complexity_reasoning',
            'recommended_approach',
        ];

        if (!$this->validateResponse($response, $requiredFields)) {
            throw new \Exception('Invalid complexity evaluation response structure');
        }

        // Validate complexity level
        if (!in_array($response['complexity_level'], [1, 2, 3, 4])) {
            throw new \Exception('Invalid complexity level: must be 1-4');
        }

        return $response;
    }

    /**
     * Build the evaluation prompt.
     */
    protected function buildPrompt(Challenge $challenge, ChallengeAnalysis $briefAnalysis): string
    {
        $objectives = json_encode($briefAnalysis->objectives);
        $constraints = json_encode($briefAnalysis->constraints);
        $successCriteria = json_encode($briefAnalysis->success_criteria);

        return <<<PROMPT
Evaluate the complexity of this challenge and determine the best approach for execution.

Challenge: {$challenge->title}

Refined Brief:
{$challenge->refined_brief}

Objectives:
{$objectives}

Constraints:
{$constraints}

Success Criteria:
{$successCriteria}

Evaluate the complexity on a scale of 1-4:
- Level 1: Simple discussion topic, can be solved through community ideation
- Level 2: Moderate challenge requiring structured ideas and voting
- Level 3: Complex challenge requiring team execution with multiple workstreams
- Level 4: Highly complex challenge requiring extensive coordination and specialized skills

Provide your evaluation in JSON format:

{
  "confidence_score": <float 0-100 indicating confidence in this evaluation>,
  "complexity_level": <integer 1-4>,
  "complexity_reasoning": "<detailed explanation of why this complexity level was chosen>",
  "recommended_approach": "<community_discussion or team_execution>",
  "estimated_duration_weeks": <integer>,
  "required_skill_areas": [
    "<skill area 1>",
    "<skill area 2>"
  ],
  "estimated_volunteer_count": <integer, for team execution only>,
  "key_challenges": [
    "<challenge 1>",
    "<challenge 2>"
  ],
  "validation_notes": "<any concerns or uncertainties>"
}

Guidelines:
- Complexity 1-2 → recommended_approach: "community_discussion"
- Complexity 3-4 → recommended_approach: "team_execution"
- Be conservative with complexity scoring - err on the side of higher complexity
- Consider: scope, technical difficulty, coordination needs, skill requirements
- Estimated duration should be realistic given the scope
- For team execution, estimate how many volunteers would be needed
- List 3-5 key skill areas required
PROMPT;
    }

    /**
     * Get the system prompt.
     */
    protected function getSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert project manager and systems architect specializing in evaluating project complexity and resource requirements. Your role is to assess challenges and determine the appropriate execution approach.

You must:
1. Provide responses in valid JSON format
2. Accurately assess complexity based on scope, technical requirements, and coordination needs
3. Make realistic estimates for duration and resource requirements
4. Distinguish between challenges that can be solved through discussion vs. those requiring implementation
5. Provide clear reasoning for all assessments
6. Be conservative - it's better to overestimate complexity than underestimate

Your evaluation determines whether a challenge goes to community discussion or team formation.
SYSTEM;
    }

    /**
     * Store the evaluation results.
     */
    public function storeResults(Challenge $challenge, array $evaluation): void
    {
        // Update challenge with complexity and type
        $challenge->update([
            'complexity_level' => $evaluation['complexity_level'],
            'challenge_type' => $evaluation['recommended_approach'],
        ]);

        // Create challenge analysis record for stage 2
        ChallengeAnalysis::create([
            'challenge_id' => $challenge->id,
            'stage' => 'complexity',
            'objectives' => null,
            'constraints' => null,
            'success_criteria' => null,
            'confidence_score' => $evaluation['confidence_score'],
            'validation_status' => $this->meetsConfidenceThreshold($evaluation['confidence_score'])
                ? 'passed'
                : 'needs_review',
            'requires_human_review' => !$this->meetsConfidenceThreshold($evaluation['confidence_score']),
            'validation_errors' => isset($evaluation['validation_notes']) && !empty($evaluation['validation_notes'])
                ? [
                    'notes' => $evaluation['validation_notes'],
                    'complexity_reasoning' => $evaluation['complexity_reasoning'],
                ]
                : ['complexity_reasoning' => $evaluation['complexity_reasoning']],
        ]);
    }
}
