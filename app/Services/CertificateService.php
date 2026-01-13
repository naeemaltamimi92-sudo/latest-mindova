<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Challenge;
use App\Models\User;
use App\Models\TaskAssignment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateService
{
    /**
     * Generate certificates for all volunteers in a challenge.
     *
     * @param Challenge $challenge
     * @param string $certificateType
     * @param string|null $companyLogoPath
     * @return array
     */
    public function generateCertificatesForChallenge(
        Challenge $challenge,
        string $certificateType = 'participation',
        ?string $companyLogoPath = null
    ): array {
        $certificates = [];
        $volunteers = $this->getChallengeVolunteers($challenge);

        foreach ($volunteers as $volunteer) {
            try {
                $certificate = $this->generateCertificate(
                    $volunteer,
                    $challenge,
                    $certificateType,
                    $companyLogoPath
                );

                $certificates[] = $certificate;
            } catch (\Exception $e) {
                Log::error('Certificate generation failed', [
                    'volunteer_id' => $volunteer->id,
                    'challenge_id' => $challenge->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $certificates;
    }

    /**
     * Generate a single certificate for a volunteer.
     *
     * @param User $volunteer
     * @param Challenge $challenge
     * @param string $certificateType
     * @param string|null $companyLogoPath
     * @return Certificate
     */
    public function generateCertificate(
        User $volunteer,
        Challenge $challenge,
        string $certificateType = 'participation',
        ?string $companyLogoPath = null
    ): Certificate {
        // Calculate time investment
        $timeData = $this->calculateTimeInvestment($volunteer, $challenge);

        // Determine role
        $role = $this->determineVolunteerRole($volunteer, $challenge);

        // Generate AI contribution summary
        $contributionSummary = $this->generateContributionSummary($volunteer, $challenge, $role);

        // Determine contribution types
        $contributionTypes = $this->determineContributionTypes($volunteer, $challenge);

        // Create certificate record
        $certificate = Certificate::create([
            'user_id' => $volunteer->id,
            'challenge_id' => $challenge->id,
            'company_id' => $challenge->company_id,
            'certificate_type' => $certificateType,
            'role' => $role,
            'contribution_summary' => $contributionSummary,
            'contribution_types' => $contributionTypes,
            'total_hours' => $timeData['total_hours'],
            'time_breakdown' => $timeData['breakdown'],
            'company_confirmed' => true,
            'confirmed_at' => now(),
            'company_logo_path' => $companyLogoPath,
            'issued_at' => now(),
        ]);

        // Generate PDF
        $this->generateCertificatePDF($certificate);

        return $certificate;
    }

    /**
     * Calculate time investment for a volunteer in a challenge.
     *
     * @param User $volunteer
     * @param Challenge $challenge
     * @return array
     */
    protected function calculateTimeInvestment(User $volunteer, Challenge $challenge): array
    {
        $assignments = TaskAssignment::where('volunteer_id', $volunteer->volunteer->id)
            ->whereHas('task', function ($query) use ($challenge) {
                $query->where('challenge_id', $challenge->id);
            })
            ->get();

        $totalHours = 0;
        $analysisHours = 0;
        $executionHours = 0;
        $reviewHours = 0;

        foreach ($assignments as $assignment) {
            // Calculate hours based on task type and status changes
            $taskHours = $this->calculateAssignmentHours($assignment);
            $totalHours += $taskHours;

            // Categorize by task type
            if ($assignment->task && str_contains(strtolower($assignment->task->title), 'analysis')) {
                $analysisHours += $taskHours;
            } elseif ($assignment->task && str_contains(strtolower($assignment->task->title), 'review')) {
                $reviewHours += $taskHours;
            } else {
                $executionHours += $taskHours;
            }
        }

        return [
            'total_hours' => round($totalHours, 2),
            'breakdown' => [
                'analysis' => round($analysisHours, 2),
                'execution' => round($executionHours, 2),
                'review' => round($reviewHours, 2),
            ]
        ];
    }

    /**
     * Calculate hours for a single assignment.
     *
     * @param TaskAssignment $assignment
     * @return float
     */
    protected function calculateAssignmentHours(TaskAssignment $assignment): float
    {
        // If assignment has explicit hours tracked, use those
        if (isset($assignment->hours_spent) && $assignment->hours_spent > 0) {
            return (float) $assignment->hours_spent;
        }

        // Otherwise, estimate based on time between accepted and completed
        if ($assignment->accepted_at && $assignment->completed_at) {
            $diffInHours = $assignment->accepted_at->diffInHours($assignment->completed_at);

            // Cap at reasonable maximum (40 hours per task)
            return min($diffInHours, 40);
        }

        // Default estimate based on task complexity
        if ($assignment->task) {
            return $this->estimateTaskHours($assignment->task);
        }

        return 0;
    }

    /**
     * Estimate task hours based on difficulty or other factors.
     *
     * @param $task
     * @return float
     */
    protected function estimateTaskHours($task): float
    {
        // Default estimates based on task complexity
        // This is a simplified version - you can make this more sophisticated
        return 8.0; // Default 8 hours
    }

    /**
     * Determine volunteer's role in the challenge.
     *
     * @param User $volunteer
     * @param Challenge $challenge
     * @return string
     */
    protected function determineVolunteerRole(User $volunteer, Challenge $challenge): string
    {
        // Get all tasks assigned to this volunteer for this challenge
        $assignments = TaskAssignment::where('volunteer_id', $volunteer->volunteer->id)
            ->whereHas('task', function ($query) use ($challenge) {
                $query->where('challenge_id', $challenge->id);
            })
            ->with('task')
            ->get();

        if ($assignments->isEmpty()) {
            return 'Contributor';
        }

        // Analyze task types to determine role
        $taskTypes = $assignments->map(function ($assignment) {
            return $assignment->task ? strtolower($assignment->task->title) : '';
        })->join(' ');

        if (str_contains($taskTypes, 'analysis') || str_contains($taskTypes, 'research')) {
            return 'Problem Analysis';
        } elseif (str_contains($taskTypes, 'implementation') || str_contains($taskTypes, 'development')) {
            return 'Technical Solution';
        } elseif (str_contains($taskTypes, 'review') || str_contains($taskTypes, 'validation')) {
            return 'Validation';
        } elseif (str_contains($taskTypes, 'on-site') || str_contains($taskTypes, 'deployment')) {
            return 'On-site Support';
        }

        return 'Technical Contributor';
    }

    /**
     * Determine contribution types.
     *
     * @param User $volunteer
     * @param Challenge $challenge
     * @return array
     */
    protected function determineContributionTypes(User $volunteer, Challenge $challenge): array
    {
        $types = [];

        $assignments = TaskAssignment::where('volunteer_id', $volunteer->volunteer->id)
            ->whereHas('task', function ($query) use ($challenge) {
                $query->where('challenge_id', $challenge->id);
            })
            ->with('task')
            ->get();

        $taskTitles = $assignments->map(function ($assignment) {
            return $assignment->task ? strtolower($assignment->task->title) : '';
        })->join(' ');

        if (str_contains($taskTitles, 'analysis') || str_contains($taskTitles, 'research')) {
            $types[] = 'Problem Analysis';
        }
        if (str_contains($taskTitles, 'implementation') || str_contains($taskTitles, 'development') || str_contains($taskTitles, 'solution')) {
            $types[] = 'Technical Solution';
        }
        if (str_contains($taskTitles, 'review') || str_contains($taskTitles, 'validation') || str_contains($taskTitles, 'testing')) {
            $types[] = 'Validation';
        }
        if (str_contains($taskTitles, 'on-site') || str_contains($taskTitles, 'deployment') || str_contains($taskTitles, 'support')) {
            $types[] = 'On-site Support';
        }

        return !empty($types) ? $types : ['Technical Contribution'];
    }

    /**
     * Generate AI-powered contribution summary.
     *
     * @param User $volunteer
     * @param Challenge $challenge
     * @param string $role
     * @return string
     */
    protected function generateContributionSummary(User $volunteer, Challenge $challenge, string $role): string
    {
        try {
            // Get volunteer's tasks and contributions
            $assignments = TaskAssignment::where('volunteer_id', $volunteer->volunteer->id)
                ->whereHas('task', function ($query) use ($challenge) {
                    $query->where('challenge_id', $challenge->id);
                })
                ->with('task')
                ->get();

            $taskDescriptions = $assignments->map(function ($assignment) {
                return $assignment->task ? $assignment->task->title . ': ' . $assignment->task->description : '';
            })->filter()->join("\n");

            // Generate summary using Anthropic Claude
            $prompt = "Generate a professional, concise (1-2 sentences) contribution summary for a certificate. The volunteer worked on: {$role}.\n\nChallenge: {$challenge->title}\nDomain: {$challenge->domain}\n\nTasks completed:\n{$taskDescriptions}\n\nWrite a professional summary that highlights the value added and impact. Start with 'Contributed to' or similar. Keep it professional and suitable for a formal certificate.";

            $response = Http::withHeaders([
                'x-api-key' => config('ai.anthropic.api_key'),
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->timeout(config('ai.anthropic.timeout', 60))
            ->post(config('ai.anthropic.base_url', 'https://api.anthropic.com') . '/v1/messages', [
                'model' => 'claude-sonnet-4-20250514',
                'max_tokens' => 150,
                'system' => 'You are a professional certificate writer. Create concise, formal contribution summaries for professional certificates. Return only the summary text, no JSON or formatting.',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            if (!$response->successful()) {
                throw new \Exception('Anthropic API error: ' . $response->body());
            }

            $responseData = $response->json();
            $summary = trim($responseData['content'][0]['text'] ?? '');

            return $summary;

        } catch (\Exception $e) {
            Log::error('AI contribution summary generation failed', [
                'volunteer_id' => $volunteer->id,
                'challenge_id' => $challenge->id,
                'error' => $e->getMessage()
            ]);

            // Fallback to manual summary
            return $this->generateFallbackSummary($volunteer, $challenge, $role);
        }
    }

    /**
     * Generate fallback summary when AI is unavailable.
     *
     * @param User $volunteer
     * @param Challenge $challenge
     * @param string $role
     * @return string
     */
    protected function generateFallbackSummary(User $volunteer, Challenge $challenge, string $role): string
    {
        return "Contributed to {$challenge->title} in the domain of {$challenge->domain}, providing valuable expertise in {$role}.";
    }

    /**
     * Get all volunteers who participated in the challenge.
     *
     * @param Challenge $challenge
     * @return \Illuminate\Support\Collection
     */
    protected function getChallengeVolunteers(Challenge $challenge)
    {
        return User::whereHas('volunteer.taskAssignments', function ($query) use ($challenge) {
            $query->whereHas('task', function ($q) use ($challenge) {
                $q->where('challenge_id', $challenge->id);
            });
        })->get();
    }

    /**
     * Generate PDF certificate.
     *
     * @param Certificate $certificate
     * @return void
     */
    protected function generateCertificatePDF(Certificate $certificate): void
    {
        // Load certificate with relationships
        $certificate->load(['volunteer', 'challenge', 'company']);

        // Generate PDF
        $pdf = Pdf::loadView('certificates.template', [
            'certificate' => $certificate
        ]);

        // Save PDF
        $filename = "certificate_{$certificate->certificate_number}.pdf";
        $path = "certificates/{$filename}";

        Storage::disk('public')->put($path, $pdf->output());

        // Update certificate with PDF path
        $certificate->update(['pdf_path' => $path]);
    }

    /**
     * Regenerate certificate PDF.
     *
     * @param Certificate $certificate
     * @return void
     */
    public function regeneratePDF(Certificate $certificate): void
    {
        // Delete old PDF if exists
        if ($certificate->pdf_path) {
            Storage::disk('public')->delete($certificate->pdf_path);
        }

        // Generate new PDF
        $this->generateCertificatePDF($certificate);
    }
}
