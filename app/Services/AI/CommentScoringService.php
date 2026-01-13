<?php

namespace App\Services\AI;

use App\Models\ChallengeComment;
use App\Models\Challenge;

class CommentScoringService extends AnthropicService
{
    protected function getModel(): string
    {
        return config('ai.models.comment_analysis', 'claude-sonnet-4-20250514');
    }

    protected function getRequestType(): string
    {
        return 'comment_scoring';
    }

    /**
     * Analyze and score a comment's quality and relevance.
     *
     * @param ChallengeComment $comment
     * @return array Analysis results with score
     */
    public function analyzeComment(ChallengeComment $comment): array
    {
        $comment->load('challenge', 'user');

        $prompt = $this->buildPrompt($comment);
        $systemPrompt = $this->getSystemPrompt();

        $response = $this->makeRequest(
            prompt: $prompt,
            options: [
                'system_prompt' => $systemPrompt,
                'temperature' => 0.3,
            ],
            relatedType: ChallengeComment::class,
            relatedId: $comment->id
        );

        // Validate response structure
        $requiredFields = [
            'score',
            'analysis',
            'relevance',
            'constructiveness',
            'clarity',
        ];

        if (!$this->validateResponse($response, $requiredFields)) {
            throw new \Exception('Invalid comment analysis response structure');
        }

        return $response;
    }

    /**
     * Build the analysis prompt.
     */
    protected function buildPrompt(ChallengeComment $comment): string
    {
        $challenge = $comment->challenge;
        $user = $comment->user;

        return <<<PROMPT
Analyze the following comment on a community challenge and score its quality and relevance.

CHALLENGE CONTEXT:
Title: {$challenge->title}
Field: {$challenge->field}
Score: {$challenge->score}/10
Description: {$challenge->original_description}

COMMENT:
Author: {$user->name}
Content: {$comment->content}

Your task is to evaluate this comment's quality and provide a score. Return your analysis in JSON format:

{
  "score": <integer 1-10>,
  "analysis": "<detailed analysis of the comment's quality and value>",
  "relevance": <float 0-10 indicating how relevant the comment is to the challenge>,
  "constructiveness": <float 0-10 indicating how constructive and helpful the comment is>,
  "clarity": <float 0-10 indicating how clear and well-articulated the comment is>,
  "key_insights": [
    "<insight 1>",
    "<insight 2>"
  ],
  "potential_value": "<how this comment could benefit the challenge discussion>",
  "concerns": "<any issues or concerns with the comment, or null if none>"
}

SCORING CRITERIA (score 1-10):
- Score 1-3: Low quality
  * Off-topic or irrelevant to the challenge
  * Lacks substance or actionable insights
  * Unclear or poorly articulated
  * Unhelpful or potentially harmful

- Score 4-6: Medium quality
  * Somewhat relevant to the challenge
  * Contains some useful ideas or perspectives
  * Moderately clear and understandable
  * Could contribute to the discussion

- Score 7-10: High quality
  * Highly relevant to the challenge and field
  * Provides actionable insights or solutions
  * Clear, well-articulated, and thoughtful
  * Adds significant value to the discussion
  * Shows expertise or deep understanding

EVALUATION GUIDELINES:
- Consider the comment's relevance to the challenge's field and objectives
- Assess whether the comment provides actionable insights or solutions
- Evaluate the clarity and professionalism of the communication
- Identify any unique perspectives or valuable contributions
- Note if the comment asks clarifying questions that could improve the challenge
- Flag spam, harassment, or completely off-topic content with very low scores
PROMPT;
    }

    /**
     * Get the system prompt.
     */
    protected function getSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert at evaluating community contributions and comment quality. Your role is to analyze comments on business challenges and assess their value, relevance, and constructiveness.

You must:
1. Provide responses in valid JSON format
2. Evaluate comments objectively based on their merit
3. Consider both the content and potential impact
4. Identify valuable insights even if unconventional
5. Be fair but critical - reward substance over length
6. Flag low-quality, spam, or harmful content with appropriate scores

Your analysis helps surface the most valuable community contributions and maintains discussion quality.
SYSTEM;
    }

    /**
     * Store the analysis results.
     */
    public function storeResults(ChallengeComment $comment, array $analysis): void
    {
        $comment->update([
            'ai_score' => $analysis['score'],
            'ai_analysis' => $analysis['analysis'],
            'ai_score_status' => 'completed',
            'ai_scored_at' => now(),
        ]);
    }
}
