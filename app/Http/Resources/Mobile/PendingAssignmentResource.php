<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\TaskAssignment */
class PendingAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'match_score' => $this->match_score,
            'task' => [
                'id' => $this->task->id,
                'title' => $this->task->title,
                'description' => $this->task->description,
                'estimated_hours' => $this->task->estimated_hours,
                'challenge' => [
                    'id' => $this->task->challenge->id,
                    'title' => $this->task->challenge->title,
                ],
            ],
        ];
    }
}
