<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\TaskAssignment */
class ActiveTaskAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->invitation_status,
            'task' => [
                'id' => $this->task->id,
                'title' => $this->task->title,
                'challenge' => [
                    'id' => $this->task->challenge->id,
                    'title' => $this->task->challenge->title,
                ],
            ],
        ];
    }
}
