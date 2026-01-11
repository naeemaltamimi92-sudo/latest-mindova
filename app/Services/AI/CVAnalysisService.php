<?php

namespace App\Services\AI;

use App\Models\Volunteer;
use App\Models\VolunteerSkill;

class CVAnalysisService extends AnthropicService
{
    protected function getModel(): string
    {
        return config('ai.models.cv_analysis', 'claude-sonnet-4-20250514');
    }

    protected function getRequestType(): string
    {
        return 'cv_analysis';
    }

    /**
     * Analyze a CV file directly using Claude's document understanding.
     *
     * @param string $filePath The storage path to the CV file
     * @param Volunteer $volunteer The volunteer to analyze for
     * @return array Analysis results with confidence score
     */
    public function analyzeFromFile(string $filePath, Volunteer $volunteer): array
    {
        $prompt = $this->buildPrompt();
        $systemPrompt = $this->getSystemPrompt();

        $response = $this->makeDocumentRequest(
            prompt: $prompt,
            filePath: $filePath,
            options: [
                'system_prompt' => $systemPrompt,
            ],
            relatedType: Volunteer::class,
            relatedId: $volunteer->id
        );

        $requiredFields = [
            'confidence_score',
            'experience_level',
            'years_of_experience',
            'skills',
            'education',
            'work_experience',
            'professional_domains',
        ];

        if (!$this->validateResponse($response, $requiredFields)) {
            throw new \Exception('Invalid CV analysis response structure');
        }

        return $response;
    }

    /**
     * Build the analysis prompt.
     */
    protected function buildPrompt(): string
    {
        return <<<PROMPT
Analyze this CV/resume document and extract structured information. Be thorough and accurate.

Please provide a comprehensive analysis in JSON format with the following structure:

{
  "confidence_score": <float 0-100 indicating your confidence in this analysis>,
  "experience_level": "<Junior|Mid|Expert|Manager>",
  "years_of_experience": <float>,
  "skills": [
    {
      "skill_name": "<name>",
      "skill_category": "<Technical|Soft|Domain|Language|Tool>",
      "proficiency_level": "<Beginner|Intermediate|Advanced|Expert>",
      "years_of_experience": <float>,
      "source": "<where this skill was mentioned, e.g., 'Work Experience at Company X'>"
    }
  ],
  "education": [
    {
      "degree": "<degree name>",
      "field_of_study": "<field>",
      "institution": "<institution name>",
      "graduation_year": <year or null>,
      "achievements": "<notable achievements or honors>"
    }
  ],
  "work_experience": [
    {
      "job_title": "<title>",
      "company": "<company name>",
      "start_date": "<YYYY-MM or YYYY>",
      "end_date": "<YYYY-MM or YYYY or 'Present'>",
      "duration_years": <float>,
      "responsibilities": "<key responsibilities>",
      "achievements": "<key achievements>"
    }
  ],
  "professional_domains": [
    "<domain1>",
    "<domain2>"
  ],
  "summary": "<brief professional summary>",
  "validation_notes": "<any concerns or uncertainties about the analysis>"
}

Guidelines:
- Set confidence_score based on CV clarity, completeness, and your certainty
- Experience level: Junior (0-2 years), Mid (2-5 years), Expert (5-10 years), Manager (10+ years or management role)
- Extract ALL skills mentioned, categorizing them appropriately
- Include soft skills, technical skills, tools, languages, and domain expertise
- Be conservative with proficiency levels - only mark as Expert if clearly demonstrated
- Professional domains should be broad categories (e.g., "Healthcare", "Finance", "Education")
- If information is missing or unclear, use null values and note in validation_notes
- Return ONLY valid JSON, no additional text
PROMPT;
    }

    /**
     * Get the system prompt for CV analysis.
     */
    protected function getSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert HR analyst and talent assessment specialist. Your role is to analyze CVs and extract structured, accurate information about candidates' skills, experience, and qualifications.

You must:
1. Provide responses in valid JSON format
2. Be thorough but accurate - don't infer information that isn't clearly stated
3. Assign realistic proficiency levels based on evidence in the CV
4. Calculate experience durations accurately
5. Categorize skills appropriately
6. Provide a confidence score reflecting the quality and completeness of the CV
7. Note any uncertainties or concerns in validation_notes

Your analysis will be used to match volunteers with appropriate tasks, so accuracy is critical.
SYSTEM;
    }

    /**
     * Store the analysis results in the database.
     */
    public function storeResults(Volunteer $volunteer, array $analysis): void
    {
        VolunteerSkill::where('volunteer_id', $volunteer->id)
            ->where('source', 'cv_analysis')
            ->delete();

        $volunteer->update([
            'experience_level' => $analysis['experience_level'],
            'years_of_experience' => $analysis['years_of_experience'],
            'education' => $analysis['education'],
            'work_experience' => $analysis['work_experience'],
            'professional_domains' => $analysis['professional_domains'],
            'ai_analysis_confidence' => $analysis['confidence_score'],
            'ai_analysis_status' => 'completed',
            'ai_analyzed_at' => now(),
            'validation_status' => $this->meetsConfidenceThreshold($analysis['confidence_score'])
                ? 'passed'
                : 'needs_review',
        ]);

        foreach ($analysis['skills'] as $skillData) {
            $proficiency = $this->mapProficiencyLevel($skillData['proficiency_level'] ?? 'intermediate');

            VolunteerSkill::create([
                'volunteer_id' => $volunteer->id,
                'skill_name' => $skillData['skill_name'],
                'category' => $skillData['skill_category'] ?? null,
                'proficiency_level' => $proficiency,
                'years_of_experience' => $skillData['years_of_experience'] ?? 0,
                'source' => 'cv_analysis',
            ]);
        }

        $volunteer->update(['skills_normalized' => true]);
    }

    /**
     * Map any proficiency level string to valid ENUM value.
     */
    protected function mapProficiencyLevel(string $level): string
    {
        $level = strtolower(trim($level));

        if (in_array($level, ['beginner', 'intermediate', 'advanced', 'expert'])) {
            return $level;
        }

        if (str_contains($level, 'expert') || str_contains($level, 'senior') || str_contains($level, 'master')) {
            return 'expert';
        }

        if (str_contains($level, 'advanced') || str_contains($level, 'proficient') || str_contains($level, 'professional')) {
            return 'advanced';
        }

        if (str_contains($level, 'intermediate') || str_contains($level, 'mid') || str_contains($level, 'competent')) {
            return 'intermediate';
        }

        if (str_contains($level, 'beginner') || str_contains($level, 'basic') || str_contains($level, 'novice') || str_contains($level, 'junior')) {
            return 'beginner';
        }

        return 'intermediate';
    }
}
