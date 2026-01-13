<?php

namespace App\Services\AI;

use App\Models\Task;
use App\Models\Volunteer;
use App\Models\TaskAssignment;
use Illuminate\Support\Collection;

class VolunteerMatchingService extends AnthropicService
{
    protected function getModel(): string
    {
        return config('ai.models.volunteer_matching', 'claude-sonnet-4-20250514');
    }

    protected function getRequestType(): string
    {
        return 'volunteer_matching';
    }

    /**
     * Find and match volunteers to a task.
     *
     * @param Task $task
     * @param int $maxMatches Maximum number of volunteers to match
     * @return array Array of match results
     */
    public function matchVolunteersToTask(Task $task, int $maxMatches = 5): array
    {
        // Get eligible volunteers (completed profile, skills analyzed)
        $volunteers = $this->getEligibleVolunteers($task);

        if ($volunteers->isEmpty()) {
            return [];
        }

        // Score matches using AI
        $matches = $this->scoreMatches($task, $volunteers, $maxMatches);

        return $matches;
    }

    /**
     * Get volunteers eligible for matching.
     */
    protected function getEligibleVolunteers(Task $task): Collection
    {
        // Load the challenge to get the field
        $task->load('challenge');
        $challengeField = $task->challenge->field ?? null;

        $query = Volunteer::with('skills', 'user')
            ->where('ai_analysis_status', 'completed')
            ->where('validation_status', 'passed')
            ->where('availability_hours_per_week', '>=', 5); // Minimum availability

        // Filter by field if challenge has a field specified
        if ($challengeField) {
            $query->where('field', $challengeField);
        }

        $volunteers = $query->get();

        \Log::info('Found volunteers before filtering', [
            'task_id' => $task->id,
            'count' => $volunteers->count(),
            'volunteer_ids' => $volunteers->pluck('id')->toArray(),
        ]);

        $filtered = $volunteers->filter(function ($volunteer) use ($task) {
                // Filter out volunteers who have ANY active assignment
                // (only one task at a time until completion)
                $hasActiveAssignment = TaskAssignment::where('volunteer_id', $volunteer->id)
                    ->whereIn('invitation_status', ['invited', 'accepted', 'in_progress', 'submitted'])
                    ->exists();

                if ($hasActiveAssignment) {
                    \Log::info('Volunteer has active assignment, skipping', [
                        'volunteer_id' => $volunteer->id,
                        'task_id' => $task->id,
                    ]);
                    return false;
                }

                // Also filter out volunteers already assigned to this specific task
                $isAssignedToThisTask = TaskAssignment::where('task_id', $task->id)
                    ->where('volunteer_id', $volunteer->id)
                    ->exists();

                return !$isAssignedToThisTask;
            });

        \Log::info('Volunteers after assignment filtering', [
            'task_id' => $task->id,
            'count' => $filtered->count(),
        ]);

        return $filtered;
    }

    /**
     * Score volunteers against a task using AI.
     */
    protected function scoreMatches(Task $task, Collection $volunteers, int $maxMatches): array
    {
        $prompt = $this->buildMatchingPrompt($task, $volunteers);
        $systemPrompt = $this->getSystemPrompt();

        $response = $this->makeRequest(
            prompt: $prompt,
            options: [
                'system_prompt' => $systemPrompt,
                'temperature' => 0.3,
            ],
            relatedType: Task::class,
            relatedId: $task->id
        );

        // Validate response
        if (!isset($response['matches']) || !is_array($response['matches'])) {
            throw new \Exception('Invalid matching response structure');
        }

        // Debug: Log match details
        \Log::info('AI returned matches', [
            'task_id' => $task->id,
            'raw_matches' => $response['matches'],
        ]);

        // Limit to maxMatches
        return array_slice($response['matches'], 0, $maxMatches);
    }

    /**
     * Build the matching prompt.
     */
    protected function buildMatchingPrompt(Task $task, Collection $volunteers): string
    {
        $taskData = [
            'title' => $task->title,
            'description' => $task->description,
            'required_skills' => $task->required_skills,
            'estimated_hours' => $task->estimated_hours,
            'complexity_score' => $task->complexity_score,
        ];

        $volunteerData = $volunteers->map(function ($volunteer) {
            return [
                'volunteer_id' => $volunteer->id,
                'field' => $volunteer->field,
                'experience_level' => $volunteer->experience_level,
                'years_of_experience' => $volunteer->years_of_experience,
                'availability_hours_per_week' => $volunteer->availability_hours_per_week,
                'professional_domains' => $volunteer->professional_domains,
                'skills' => $volunteer->skills->map(function ($skill) {
                    return [
                        'name' => $skill->skill_name,
                        'category' => $skill->skill_category,
                        'proficiency' => $skill->proficiency_level,
                        'years' => $skill->years_of_experience,
                    ];
                })->toArray(),
                'bio' => $volunteer->bio,
            ];
        })->toArray();

        $taskJson = json_encode($taskData, JSON_PRETTY_PRINT);
        $volunteersJson = json_encode($volunteerData, JSON_PRETTY_PRINT);

        return <<<PROMPT
Match volunteers to the following task. Evaluate skill fit, experience level, and availability.

Task:
{$taskJson}

Available Volunteers:
{$volunteersJson}

Analyze each volunteer and provide matches in JSON format:

{
  "confidence_score": <float 0-100>,
  "matches": [
    {
      "volunteer_id": <id>,
      "match_score": <float 0-100>,
      "skill_match_percentage": <float 0-100>,
      "experience_match": "<Excellent|Good|Adequate|Insufficient>",
      "availability_sufficient": <boolean>,
      "reasoning": "<detailed explanation of why this volunteer is a good match>",
      "strengths": ["<strength 1>", "<strength 2>"],
      "gaps": ["<gap 1>", "<gap 2>"],
      "recommended": <boolean>
    }
  ],
  "task_analysis": "<brief analysis of what makes this task challenging>"
}

Guidelines:
- Match score 80-100: Excellent match, highly recommended
- Match score 60-79: Good match, recommended with minor gaps
- Match score 40-59: Adequate match, acceptable but not ideal
- Match score 0-39: Poor match, not recommended
- Consider: skill overlap, proficiency levels, experience level, availability
- Skill match percentage: what % of required skills does the volunteer have
- Only recommend volunteers with match_score >= 60
- Order matches by match_score (highest first)
- Be realistic about gaps - note any missing skills or experience
- Maximum 10 matches, but only include those worth considering
PROMPT;
    }

    /**
     * Get the system prompt.
     */
    protected function getSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert talent matcher specializing in matching volunteers to project tasks based on skills, experience, and availability. Your role is to evaluate volunteer-task fit and provide accurate match scores.

You must:
1. Provide responses in valid JSON format
2. Evaluate skill overlap accurately
3. Consider both hard skills and experience level
4. Account for proficiency levels (Expert > Advanced > Intermediate > Beginner)
5. Flag availability concerns
6. Provide clear, actionable reasoning
7. Be honest about gaps and limitations
8. Only recommend volunteers who are genuinely suitable

Your matches will be used to assign volunteers to real work, so accuracy is critical.
SYSTEM;
    }

    /**
     * Create task assignments from match results.
     */
    public function createAssignments(Task $task, array $matches): array
    {
        $assignments = [];

        foreach ($matches as $match) {
            // Only create assignments for good matches (score >= 60)
            // Even if not explicitly recommended, assign if the score is good
            $matchScore = $match['match_score'] ?? 0;
            if ($matchScore < 60) {
                \Log::info('Skipping low-score match', [
                    'task_id' => $task->id,
                    'volunteer_id' => $match['volunteer_id'],
                    'match_score' => $matchScore,
                ]);
                continue;
            }

            $assignment = TaskAssignment::create([
                'task_id' => $task->id,
                'volunteer_id' => $match['volunteer_id'],
                'ai_match_score' => $match['match_score'],
                'ai_match_reasoning' => json_encode([
                    'reasoning' => $match['reasoning'],
                    'strengths' => $match['strengths'],
                    'gaps' => $match['gaps'],
                    'skill_match_percentage' => $match['skill_match_percentage'],
                    'experience_match' => $match['experience_match'],
                ]),
                'invitation_status' => 'invited',
                'invited_at' => now(),
            ]);

            $assignments[] = $assignment;
        }

        return $assignments;
    }
}
