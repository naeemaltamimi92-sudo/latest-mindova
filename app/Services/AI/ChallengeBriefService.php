<?php

namespace App\Services\AI;

use App\Models\Challenge;
use App\Models\ChallengeAnalysis;
use App\Models\Volunteer;

class ChallengeBriefService extends AnthropicService
{
    protected function getModel(): string
    {
        return config('ai.models.challenge_analysis', 'claude-sonnet-4-20250514');
    }

    protected function getRequestType(): string
    {
        return 'challenge_brief';
    }

    /**
     * Get existing volunteer fields from database for field matching.
     */
    protected function getExistingVolunteerFields(): array
    {
        $fields = Volunteer::select('field')
            ->whereNotNull('field')
            ->where('field', '!=', '')
            ->distinct()
            ->pluck('field')
            ->toArray();

        // Fallback to generic fields if no volunteers exist yet
        if (empty($fields)) {
            return [
                'Healthcare', 'Technology', 'Finance', 'Education',
                'Engineering', 'Manufacturing', 'Marketing', 'Environment'
            ];
        }

        return $fields;
    }

    /**
     * Analyze and refine a challenge brief.
     *
     * @param Challenge $challenge
     * @return array Analysis results with refined brief
     */
    public function analyze(Challenge $challenge): array
    {
        $prompt = $this->buildPrompt($challenge);
        $systemPrompt = $this->getSystemPrompt();

        $response = $this->makeRequest(
            prompt: $prompt,
            options: [
                'system_prompt' => $systemPrompt,
                'temperature' => 0.5,
            ],
            relatedType: Challenge::class,
            relatedId: $challenge->id
        );

        // Check if the response indicates an invalid/rejected challenge
        if (isset($response['is_valid']) && $response['is_valid'] === false) {
            // For invalid challenges, only require rejection_reason
            if (!isset($response['rejection_reason'])) {
                throw new \Exception('Invalid rejection response - missing rejection_reason');
            }
            return $response;
        }

        // For valid challenges, validate full response structure
        $requiredFields = [
            'confidence_score',
            'refined_brief',
            'objectives',
            'constraints',
            'success_criteria',
            'score',
            'field',
        ];

        if (!$this->validateResponse($response, $requiredFields)) {
            throw new \Exception('Invalid brief analysis response structure');
        }

        // Ensure is_valid is set for valid responses
        $response['is_valid'] = true;

        return $response;
    }

    /**
     * Build the analysis prompt.
     */
    protected function buildPrompt(Challenge $challenge): string
    {
        // Get existing volunteer fields for matching
        $existingFields = $this->getExistingVolunteerFields();
        $fieldsListForPrompt = implode(', ', $existingFields);

        // Get attachment content if available
        $attachmentContent = $this->getAttachmentContent($challenge);

        $attachmentSection = '';
        if ($attachmentContent) {
            $attachmentSection = <<<ATTACHMENTS


Supporting Attachments:
{$attachmentContent}

ATTACHMENTS;
        }

        return <<<PROMPT
Analyze the following challenge submission and create a refined, structured brief.

Challenge Title: {$challenge->title}

Original Description:
{$challenge->original_description}{$attachmentSection}

CRITICAL: FIRST, determine if this is a VALID challenge submission. You MUST reject and return is_valid=false if ANY of these apply:
- It contains gibberish, random characters, or nonsensical text (e.g., "dsafdsfds", "fffffffff", "aaaaaa")
- The description is mostly repeated characters or patterns
- It's clearly spam, test data, or placeholder content (e.g., "asdfasdf", "test123", "lorem ipsum", "xxx")
- It lacks any meaningful problem statement or request
- It's offensive, abusive, or completely irrelevant content
- It's too vague to understand what is being requested (just random words without context)
- The title AND description together don't form a coherent challenge request
- You cannot identify at least ONE clear objective from the submission

IMPORTANT: If the submission is nonsense or unintelligible, you MUST return is_valid=false. Do NOT try to interpret or give meaning to random text. Do NOT proceed with analysis if the content is gibberish.

If INVALID (reject the submission), respond ONLY with:
{
  "is_valid": false,
  "rejection_reason": "<clear explanation of why this submission was rejected>"
}

If VALID, provide your full analysis in JSON format:

{
  "is_valid": true,
  "confidence_score": <float 0-100 indicating clarity and feasibility>,
  "score": <integer 1-10 based on feasibility, impact and clarity>,
  "field": "<MUST be one of the existing volunteer fields listed in FIELD IDENTIFICATION section>",
  "refined_brief": "<clear, well-structured description of the challenge>",
  "objectives": [
    "<objective 1>",
    "<objective 2>"
  ],
  "constraints": [
    "<constraint 1>",
    "<constraint 2>"
  ],
  "success_criteria": [
    "<criterion 1>",
    "<criterion 2>"
  ],
  "key_stakeholders": [
    "<stakeholder 1>",
    "<stakeholder 2>"
  ],
  "potential_risks": [
    "<risk 1>",
    "<risk 2>"
  ],
  "recommended_approach": "<high-level approach to solving this challenge>",
  "validation_notes": "<any concerns, ambiguities, or questions about the challenge>"
}

Guidelines for VALID submissions:
- Refined brief should be 2-3 paragraphs, clear and professional
- Extract 3-5 concrete objectives from the description
- Identify realistic constraints (time, resources, technical, regulatory)
- Define measurable success criteria
- Identify stakeholders who would be impacted
- Note potential risks or challenges
- Suggest a high-level approach (e.g., research, prototype, implementation)
- Set confidence_score based on clarity and completeness of the original submission
- Note any ambiguities or missing information in validation_notes

SCORING SYSTEM (score 1-10):
- Score 1-2: Very complex/unclear challenges needing community discussion
  * Unclear requirements or objectives
  * Too broad or ambiguous scope
  * Requires significant clarification
  * Best suited for community brainstorming
- Score 3-6: Medium complexity challenges suitable for task breakdown
  * Clear enough to decompose into tasks
  * Moderate complexity and scope
  * Can be assigned to volunteers with specific skills
  * Reasonable timeline and resources needed
- Score 7-10: High clarity and feasibility challenges
  * Well-defined objectives and scope
  * Clear success criteria
  * Straightforward implementation path
  * High confidence in deliverability

FIELD IDENTIFICATION:
- You MUST choose from these existing volunteer fields: {$fieldsListForPrompt}
- Choose the field that best matches the challenge domain
- If no field matches exactly, choose the closest/most relevant one from the list
- Do NOT invent new field names - only use fields from the list above
- This ensures challenges can be matched with volunteers who have the relevant expertise
PROMPT;
    }

    /**
     * Get the system prompt.
     */
    protected function getSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert business analyst and project manager specializing in clarifying and structuring business challenges. Your role is to take rough challenge descriptions and transform them into clear, actionable briefs.

CRITICAL RULE: You MUST reject submissions that are gibberish, nonsensical, or incomprehensible. If you cannot understand what the submitter is asking for, return is_valid=false. Do NOT try to make sense of random text or repeated characters. Be strict about this - it's better to reject unclear submissions than to waste resources processing them.

You must:
1. FIRST check if the submission is valid/comprehensible - reject gibberish immediately with is_valid=false
2. Provide responses in valid JSON format
3. Identify and articulate clear objectives (if none can be identified, reject the submission)
4. Recognize constraints and success criteria
5. Maintain the original intent while improving clarity
6. Ask critical questions through validation_notes when information is missing
7. Provide realistic confidence scores based on the quality of the original submission

Your analysis will guide the next stages of challenge evaluation and task decomposition.
SYSTEM;
    }

    /**
     * Store the analysis results.
     */
    public function storeResults(Challenge $challenge, array $analysis): void
    {
        // Check if this is a rejected/invalid challenge
        if (isset($analysis['is_valid']) && $analysis['is_valid'] === false) {
            $challenge->update([
                'status' => 'rejected',
                'ai_analysis_status' => 'completed',
                'rejection_reason' => $analysis['rejection_reason'] ?? 'Challenge content was deemed invalid or incomprehensible.',
            ]);
            return;
        }

        // Update challenge with refined brief, score, and field
        $challenge->update([
            'refined_brief' => $analysis['refined_brief'],
            'score' => $analysis['score'],
            'field' => $analysis['field'],
            'status' => 'analyzing',
            'ai_analysis_status' => 'processing',
        ]);

        // Create challenge analysis record for stage 1
        ChallengeAnalysis::create([
            'challenge_id' => $challenge->id,
            'stage' => 'brief',
            'objectives' => $analysis['objectives'],
            'constraints' => $analysis['constraints'],
            'success_criteria' => $analysis['success_criteria'],
            'stakeholders' => $analysis['key_stakeholders'] ?? [],
            'risk_assessment' => $analysis['potential_risks'] ?? [],
            'recommended_approach' => $analysis['recommended_approach'] ?? null,
            'confidence_score' => $analysis['confidence_score'],
            'validation_status' => $this->meetsConfidenceThreshold($analysis['confidence_score'])
                ? 'passed'
                : 'needs_review',
            'requires_human_review' => !$this->meetsConfidenceThreshold($analysis['confidence_score']),
            'validation_errors' => isset($analysis['validation_notes']) && !empty($analysis['validation_notes'])
                ? ['notes' => $analysis['validation_notes']]
                : null,
        ]);
    }

    /**
     * Get attachment content for AI analysis
     *
     * @param Challenge $challenge
     * @return string|null
     */
    protected function getAttachmentContent(Challenge $challenge): ?string
    {
        // Check if challenge has attachments
        $attachments = $challenge->attachments()->processed()->get();

        if ($attachments->isEmpty()) {
            return null;
        }

        $content = '';

        foreach ($attachments as $attachment) {
            $content .= "\n--- File: {$attachment->file_name} ({$attachment->file_type}) ---\n";
            $content .= $attachment->extracted_text . "\n";
        }

        return $content;
    }
}
