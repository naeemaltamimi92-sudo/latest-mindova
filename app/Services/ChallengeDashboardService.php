<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Challenge;
use App\Models\TaskAssignment;
use App\Models\WorkSubmission;
use Illuminate\Pagination\LengthAwarePaginator;

class ChallengeDashboardService
{
    /**
     * Paginated, stat-annotated challenge list for a company's "My
     * Challenges" dashboard, plus the aggregate counts for the header.
     *
     * @return array{challenges: LengthAwarePaginator, stats: array}
     */
    public function companyChallenges(Company $company, array $filters): array
    {
        $query = Challenge::where('company_id', $company->id)
            ->with(['tasks', 'workstreams'])
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn ($q) => $q->where('status', 'completed'),
                'tasks as in_progress_tasks_count' => fn ($q) => $q->where('status', 'in_progress'),
            ])
            ->withSum('tasks', 'estimated_hours');

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type']) && $filters['type'] !== 'all') {
            $query->where('challenge_type', $filters['type']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('refined_brief', 'like', "%{$search}%");
            });
        }

        match ($filters['sort'] ?? 'newest') {
            'oldest' => $query->oldest(),
            'title' => $query->orderBy('title', 'asc'),
            'status' => $query->orderBy('status', 'asc'),
            default => $query->latest(),
        };

        $challenges = $query->paginate(12)->withQueryString();

        $this->annotateWithStats($challenges);

        $stats = [
            'total' => $company->challenges()->count(),
            'active' => $company->challenges()->where('status', 'active')->count(),
            'completed' => $company->challenges()->where('status', 'completed')->count(),
            'analyzing' => $company->challenges()->whereIn('status', ['submitted', 'analyzing'])->count(),
        ];

        return ['challenges' => $challenges, 'stats' => $stats];
    }

    /**
     * Attach progress/volunteer/submission stats to each challenge in the
     * page, using bulk-loaded lookups instead of a query per challenge.
     */
    private function annotateWithStats(LengthAwarePaginator $challenges): void
    {
        $taskIdsByChallenge = [];
        foreach ($challenges as $challenge) {
            $taskIdsByChallenge[$challenge->id] = $challenge->tasks->pluck('id')->toArray();
        }
        $allTaskIds = empty($taskIdsByChallenge) ? [] : array_merge(...array_values($taskIdsByChallenge));

        $activeVolunteers = [];
        $submissionCounts = [];
        $approvedCounts = [];

        if (!empty($allTaskIds)) {
            $activeVolunteers = TaskAssignment::whereIn('task_id', $allTaskIds)
                ->whereIn('invitation_status', ['accepted', 'in_progress'])
                ->selectRaw('task_id, COUNT(DISTINCT volunteer_id) as cnt')
                ->groupBy('task_id')
                ->pluck('cnt', 'task_id')
                ->toArray();

            $submissionCounts = WorkSubmission::whereIn('task_id', $allTaskIds)
                ->selectRaw('task_id, COUNT(*) as cnt')
                ->groupBy('task_id')
                ->pluck('cnt', 'task_id')
                ->toArray();

            $approvedCounts = WorkSubmission::whereIn('task_id', $allTaskIds)
                ->where('solves_task', true)
                ->selectRaw('task_id, COUNT(*) as cnt')
                ->groupBy('task_id')
                ->pluck('cnt', 'task_id')
                ->toArray();
        }

        foreach ($challenges as $challenge) {
            $totalTasks = $challenge->tasks_count ?? 0;

            if ($totalTasks > 0) {
                $completedTasks = $challenge->completed_tasks_count ?? 0;
                $challenge->progress_percentage = round(($completedTasks / $totalTasks) * 100);
                $challenge->total_tasks = $totalTasks;
                $challenge->completed_tasks = $completedTasks;
                $challenge->in_progress_tasks = $challenge->in_progress_tasks_count ?? 0;
                $challenge->total_estimated_hours = $challenge->tasks_sum_estimated_hours ?? 0;

                $ids = $taskIdsByChallenge[$challenge->id] ?? [];
                $challenge->active_volunteers = array_sum(array_intersect_key($activeVolunteers, array_flip($ids)));
                $challenge->submissions_count = array_sum(array_intersect_key($submissionCounts, array_flip($ids)));
                $challenge->approved_count = array_sum(array_intersect_key($approvedCounts, array_flip($ids)));
            } else {
                $challenge->progress_percentage = 0;
                $challenge->total_tasks = 0;
                $challenge->completed_tasks = 0;
                $challenge->in_progress_tasks = 0;
                $challenge->total_estimated_hours = 0;
                $challenge->active_volunteers = 0;
                $challenge->submissions_count = 0;
                $challenge->approved_count = 0;
            }
        }
    }
}
