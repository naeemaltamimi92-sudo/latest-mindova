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

        // Ensure recommended_approach is consistent with complexity_level
        $expectedApproach = $response['complexity_level'] >= 3 ? 'team_execution' : 'community_discussion';

        if ($response['recommended_approach'] !== $expectedApproach) {
            \Log::warning('Complexity/approach mismatch detected, correcting', [
                'complexity_level' => $response['complexity_level'],
                'original_approach' => $response['recommended_approach'],
                'corrected_approach' => $expectedApproach,
            ]);
            $response['recommended_approach'] = $expectedApproach;
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

- Level 1 (Simple Discussion): Brainstorming, ideation, opinion-gathering. No implementation required.
  Examples: "How can we improve customer engagement?", "What features would users want?"

- Level 2 (Moderate Discussion): Structured problem-solving through community input. Output is recommendations, not deliverables.
  Examples: "Design a strategy for entering new market", "Evaluate remote work policy options"

- Level 3 (Team Execution): Requires building/creating something tangible with concrete deliverables.
  Examples: "Build a mobile app prototype", "Create a marketing campaign"

- Level 4 (Complex Execution): Large-scale implementation with multiple workstreams and specialized expertise.
  Examples: "Develop a complete platform", "Execute organization-wide transformation"

CRITICAL: If the challenge asks "What should we do?" or "How should we think about X?" -> Level 1-2
If the challenge asks "Build/Create/Implement X" -> Level 3-4

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
- Score complexity accurately based on actual requirements
- Simple discussions and ideation requests should be Level 1-2
- Only rate as Level 3-4 if implementation/execution is truly required
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
You are an expert project manager specializing in evaluating project complexity and routing challenges to appropriate workflows.

Your PRIMARY goal is ACCURATE classification, not conservative classification.

CRITICAL RULES:
1. Provide responses in valid JSON format
2. Assess complexity based on ACTUAL requirements, not perceived importance
3. Make realistic estimates for duration and resources
4. Clearly distinguish between:
   - Discussion challenges (seeking ideas, opinions, recommendations) -> Level 1-2
   - Execution challenges (requiring building, creating, implementing) -> Level 3-4
5. Provide clear reasoning for your assessment
6. Do NOT inflate complexity - misclassification wastes community resources

Your evaluation determines workflow routing:
- Level 1-2: Goes to community discussion (ideation, voting)
- Level 3-4: Goes to team formation (task decomposition, volunteers)

Complexity is determined by WHAT NEEDS TO BE DONE, not by topic importance.
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
