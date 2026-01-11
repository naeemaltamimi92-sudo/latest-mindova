<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'challenge_id' => $this->challenge_id,
            'workstream_id' => $this->workstream_id,
            'title' => $this->title,
            'description' => $this->description,
            'required_skills' => $this->required_skills,
            'estimated_hours' => $this->estimated_hours,
            'complexity_score' => $this->complexity_score,
            'status' => $this->status,
            'deadline' => $this->deadline,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'workstream' => new WorkstreamResource($this->whenLoaded('workstream')),
            'assignments' => TaskAssignmentResource::collection($this->whenLoaded('assignments')),
        ];
    }
}
