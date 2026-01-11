<?php

namespace App\Services\AI;

use App\Models\Idea;
use App\Models\Challenge;

class IdeaScoringService extends OpenAIService
{
    protected function getModel(): string
    {
        return config('ai.models.idea_scoring', 'gpt-4o-mini');
    }

    protected function getRequestType(): string
    {
        return 'idea_scoring';
    }

    /**
     * Score an idea against a challenge.
     *
     * @param Idea $idea
     * @param Challenge $challenge
     * @return array Scoring results
     */
    public function scoreIdea(Idea $idea, Challenge $challenge): array
    {
        $prompt = $this->buildPrompt($idea, $challenge);
        $systemPrompt = $this->getSystemPrompt();

        $response = $this->makeRequest(
            prompt: $prompt,
            options: [
                'system_prompt' => $systemPrompt,
                'temperature' => 0.4,
            ],
            relatedType: Idea::class,
            relatedId: $idea->id
        );

        // Validate response structure
        $requiredFields = [
            'confidence_score',
            'ai_score',
            'feasibility_score',
            'innovation_score',
            'impact_score',
            'feedback',
        ];

        if (!$this->validateResponse($response, $requiredFields)) {
            throw new \Exception('Invalid idea scoring response structure');
        }

        // Validate score ranges
        foreach (['ai_score', 'feasibility_score', 'innovation_score', 'impact_score'] as $field) {
            if ($response[$field] < 0 || $response[$field] > 100) {
                throw new \Exception("Invalid {$field}: must be 0-100");
            }
        }

        return $response;
    }

    /**
     * Build the scoring prompt.
     */
    protected function buildPrompt(Idea $idea, Challenge $challenge): string
    {
        // Get challenge brief analysis
        $briefAnalysis = $challenge->challengeAnalyses()
            ->where('stage', 'brief')
            ->latest()
            ->first();

        $objectives = $briefAnalysis ? json_encode($briefAnalysis->objectives) : '[]';
        $constraints = $briefAnalysis ? json_encode($briefAnalysis->constraints) : '[]';
        $successCriteria = $briefAnalysis ? json_encode($briefAnalysis->success_criteria) : '[]';

        return <<<PROMPT
Evaluate the following idea submitted for a community discussion challenge.

Challenge: {$challenge->title}

Challenge Brief:
{$challenge->refined_brief}

Objectives:
{$objectives}

Constraints:
{$constraints}

Success Criteria:
{$successCriteria}

Submitted Idea:
Title: {$idea->title}

Description:
{$idea->description}

Score this idea across multiple dimensions and provide constructive feedback. Return your evaluation in JSON format:

{
  "confidence_score": <float 0-100 indicating confidence in this evaluation>,
  "ai_score": <float 0-100 overall score>,
  "feasibility_score": <float 0-100 how practical/implementable>,
  "innovation_score": <float 0-100 how creative/novel>,
  "impact_score": <float 0-100 potential impact on solving the challenge>,
  "alignment_score": <float 0-100 how well it aligns with objectives>,
  "feedback": "<constructive feedback explaining scores, highlighting strengths and areas for improvement>",
  "strengths": [
    "<strength 1>",
    "<strength 2>"
  ],
  "weaknesses": [
    "<weakness 1>",
    "<weakness 2>"
  ],
  "suggestions": [
    "<suggestion 1>",
    "<suggestion 2>"
  ],
  "addresses_objectives": <boolean, does it address the core objectives?>,
  "respects_constraints": <boolean, does it respect stated constraints?>,
  "meets_success_criteria": <boolean, would it meet success criteria if implemented?>
}

Scoring Guidelines:
- Overall AI Score: Weighted average (Feasibility 30%, Innovation 25%, Impact 30%, Alignment 15%)
- Feasibility: Can this be realistically implemented? Consider resources, time, technical difficulty
- Innovation: How creative or novel is this approach? Avoid rewarding complexity for its own sake
- Impact: How significantly would this solve the challenge if implemented well?
- Alignment: How well does it address the stated objectives and success criteria?

Feedback Guidelines:
- Be constructive and encouraging
- Highlight 2-3 specific strengths
- Note 1-2 areas for improvement
- Provide 1-2 concrete suggestions to strengthen the idea
- Keep feedback professional and actionable
- Aim for 3-4 sentences
PROMPT;
    }

    /**
     * Get the system prompt.
     */
    protected function getSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert innovation consultant and evaluator specializing in assessing ideas and solutions for business challenges. Your role is to provide fair, constructive, and insightful evaluations.

You must:
1. Provide responses in valid JSON format
2. Score ideas objectively across multiple dimensions
3. Provide constructive, actionable feedback
4. Balance encouragement with honest assessment
5. Consider feasibility alongside innovation
6. Identify both strengths and areas for improvement
7. Make realistic assessments based on the challenge context

Your scores will be combined with community votes to determine the best ideas.
SYSTEM;
    }

    /**
     * Store the scoring results.
     */
    public function storeResults(Idea $idea, array $scoring): void
    {
        $idea->update([
            'ai_score' => $scoring['ai_score'],
            'ai_feedback' => json_encode([
                'feedback' => $scoring['feedback'],
                'strengths' => $scoring['strengths'],
                'weaknesses' => $scoring['weaknesses'],
                'suggestions' => $scoring['suggestions'],
                'feasibility_score' => $scoring['feasibility_score'],
                'innovation_score' => $scoring['innovation_score'],
                'impact_score' => $scoring['impact_score'],
                'alignment_score' => $scoring['alignment_score'],
                'addresses_objectives' => $scoring['addresses_objectives'],
                'respects_constraints' => $scoring['respects_constraints'],
                'meets_success_criteria' => $scoring['meets_success_criteria'],
            ]),
            'status' => 'scored',
        ]);

        // Update final score (AI score + community votes)
        $this->updateFinalScore($idea);
    }

    /**
     * Update the final score combining AI and community votes.
     */
    protected function updateFinalScore(Idea $idea): void
    {
        // AI score weight: 40%, Community votes weight: 60%
        $aiWeight = 0.4;
        $communityWeight = 0.6;

        // Normalize community votes to 0-100 scale
        // Assuming votes range from -10 to +10 per voter on average
        $normalizedCommunityScore = max(0, min(100, 50 + ($idea->community_votes * 5)));

        $finalScore = ($idea->ai_score * $aiWeight) + ($normalizedCommunityScore * $communityWeight);

        $idea->update(['final_score' => $finalScore]);
    }
}
