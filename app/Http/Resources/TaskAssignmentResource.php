<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'volunteer_id' => $this->volunteer_id,
            'match_score' => $this->match_score,
            'match_reasoning' => $this->match_reasoning,
            'invitation_status' => $this->invitation_status,
            'responded_at' => $this->responded_at,
            'completed_at' => $this->completed_at,
            'actual_hours' => $this->actual_hours,
            'created_at' => $this->created_at,
            'volunteer' => new VolunteerResource($this->whenLoaded('volunteer')),
            'task' => new TaskResource($this->whenLoaded('task')),
        ];
    }
}
