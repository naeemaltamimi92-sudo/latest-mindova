<?php

namespace App\Services\AI;

use App\Models\Challenge;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Volunteer;
use Illuminate\Support\Collection;

class TeamFormationService extends OpenAIService
{
    protected string $model = 'gpt-4o';
    protected float $temperature = 0.3;

    /**
     * Get the AI model to use.
     */
    protected function getModel(): string
    {
        return $this->model;
    }

    /**
     * Get the request type for logging.
     */
    protected function getRequestType(): string
    {
        return 'volunteer_matching';
    }

    /**
     * Form teams for a challenge based on tasks and matched volunteers.
     */
    public function formTeams(Challenge $challenge, Collection $matchedVolunteers): array
    {
        $challengeData = $this->prepareChallengeData($challenge);
        $volunteersData = $this->prepareVolunteersData($matchedVolunteers);

        $prompt = $this->buildPrompt($challengeData, $volunteersData);

        $response = $this->makeRequest($prompt, [
            'challenge_id' => $challenge->id,
            'operation' => 'team_formation',
        ]);

        return $this->parseTeamFormationResponse($response);
    }

    /**
     * Prepare challenge data for AI analysis.
     */
    protected function prepareChallengeData(Challenge $challenge): array
    {
        $challenge->load(['workstreams.tasks']);

        $workstreams = $challenge->workstreams->map(function ($workstream) {
            return [
                'id' => $workstream->id,
                'title' => $workstream->title,
                'description' => $workstream->description,
                'tasks' => $workstream->tasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'required_skills' => $task->required_skills,
                        'estimated_hours' => $task->estimated_hours,
                        'complexity_score' => $task->complexity_score ?? 5,
                    ];
                }),
            ];
        });

        return [
            'challenge_id' => $challenge->id,
            'title' => $challenge->title,
            'description' => $challenge->description,
            'workstreams' => $workstreams,
        ];
    }

    /**
     * Prepare volunteers data for AI analysis.
     */
    protected function prepareVolunteersData(Collection $volunteers): array
    {
        return $volunteers->map(function ($volunteer) {
            return [
                'volunteer_id' => $volunteer->id,
                'experience_level' => $volunteer->experience_level ?? 'intermediate',
                'years_of_experience' => $volunteer->years_of_experience ?? 2,
                'availability_hours' => $volunteer->availability_hours_per_week,
                'professional_domains' => $volunteer->professional_domains ?? [],
                'skills' => $volunteer->skills->map(function ($skill) {
                    return [
                        'name' => $skill->skill_name,
                        'proficiency' => $skill->proficiency_level,
                        'category' => $skill->skill_category,
                    ];
                })->toArray(),
                'reputation_score' => $volunteer->reputation_score,
            ];
        })->toArray();
    }

    /**
     * Build the AI prompt for team formation.
     */
    protected function buildPrompt(array $challengeData, array $volunteersData): string
    {
        $workstreamsJson = json_encode($challengeData['workstreams'], JSON_PRETTY_PRINT);
        $volunteersJson = json_encode($volunteersData, JSON_PRETTY_PRINT);

        return <<<PROMPT
You are an expert team formation specialist and organizational psychologist. Your task is to create optimal "micro companies" (teams) for solving complex challenges.

# Challenge Context
Title: {$challengeData['title']}
Description: {$challengeData['description']}

# Workstreams and Tasks
```
{$workstreamsJson}
```

# Available Volunteers
```
{$volunteersJson}
```

# Your Task
Analyze the challenge requirements and available volunteers, then form 1-3 optimal teams (micro companies). Each team should:

1. **Complementary Skills**: Members should have diverse but complementary skills covering all required areas
2. **Balanced Experience**: Mix of senior/experienced members with junior members for mentorship
3. **Sufficient Capacity**: Total team availability should match estimated workload
4. **Clear Leadership**: Identify the most suitable team leader based on experience and skills
5. **Role Clarity**: Define each member's role and responsibilities
6. **Skill Coverage**: Ensure all critical skills from tasks are covered

# Team Formation Guidelines
- **Team Size**: 3-7 members per team (optimal is 4-5)
- **Leadership**: Choose leader based on highest experience + reputation + skill breadth
- **Specialists**: Identify members with deep expertise in specific areas
- **Coverage**: Each team should cover at least 80% of required skills
- **Balance**: Distribute strong performers across teams if forming multiple teams

# Output Format
Return a JSON object with this structure:
{
    "teams": [
        {
            "name": "Team name (creative, related to challenge focus)",
            "description": "Brief team purpose and focus area",
            "objectives": ["Primary objective 1", "Primary objective 2"],
            "leader_volunteer_id": 123,
            "members": [
                {
                    "volunteer_id": 123,
                    "role": "leader|member|specialist",
                    "role_description": "Specific responsibilities this member will handle",
                    "assigned_skills": ["Skill 1", "Skill 2"],
                    "reasoning": "Why this volunteer is a good fit"
                }
            ],
            "skills_coverage": {
                "covered_skills": ["Skill 1", "Skill 2"],
                "missing_skills": ["Skill 3"],
                "coverage_percentage": 85
            },
            "team_match_score": 85.5,
            "estimated_total_hours": 120,
            "strengths": ["Strength 1", "Strength 2"],
            "risks": ["Risk 1", "Risk 2"]
        }
    ],
    "formation_strategy": "Explanation of how teams were formed and why",
    "confidence_score": 85
}

# Important Notes
- Every volunteer should be assigned to exactly ONE team
- Leader must also be listed in members array with role="leader"
- Be realistic about skill coverage - it's ok if some minor skills are missing
- Consider reputation scores when choosing leaders
- Ensure availability hours are sufficient for estimated work

Now form the optimal team(s) for this challenge:
PROMPT;
    }

    /**
     * Parse AI response into structured team formation data.
     */
    protected function parseTeamFormationResponse(array $response): array
    {
        $content = $response['choices'][0]['message']['content'] ?? '';

        // Extract JSON from markdown code blocks if present
        if (preg_match('/```json\s*(.*?)\s*```/s', $content, $matches)) {
            $content = $matches[1];
        } elseif (preg_match('/```\s*(.*?)\s*```/s', $content, $matches)) {
            $content = $matches[1];
        }

        $data = json_decode($content, true);

        if (!$data || !isset($data['teams'])) {
            throw new \Exception('Invalid team formation response from AI');
        }

        return [
            'teams' => $data['teams'],
            'formation_strategy' => $data['formation_strategy'] ?? '',
            'confidence_score' => $data['confidence_score'] ?? 0,
        ];
    }

    /**
     * Create teams in database from AI response.
     */
    public function createTeamsFromAIResponse(Challenge $challenge, array $teamFormationData): Collection
    {
        $createdTeams = collect();

        foreach ($teamFormationData['teams'] as $teamData) {
            // Create team
            $team = Team::create([
                'challenge_id' => $challenge->id,
                'name' => $teamData['name'],
                'description' => $teamData['description'],
                'status' => 'forming',
                'leader_id' => $teamData['leader_volunteer_id'],
                'objectives' => $teamData['objectives'] ?? [],
                'skills_coverage' => $teamData['skills_coverage'] ?? [],
                'team_match_score' => $teamData['team_match_score'] ?? null,
                'estimated_total_hours' => $teamData['estimated_total_hours'] ?? null,
            ]);

            // Create team members
            foreach ($teamData['members'] as $memberData) {
                TeamMember::create([
                    'team_id' => $team->id,
                    'volunteer_id' => $memberData['volunteer_id'],
                    'role' => $memberData['role'],
                    'status' => 'invited',
                    'role_description' => $memberData['role_description'] ?? null,
                    'assigned_skills' => $memberData['assigned_skills'] ?? [],
                    'invited_at' => now(),
                ]);
            }

            $createdTeams->push($team);
        }

        return $createdTeams;
    }
}
