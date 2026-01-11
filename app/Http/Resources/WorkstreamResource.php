<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkstreamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'challenge_id' => $this->challenge_id,
            'title' => $this->title,
            'description' => $this->description,
            'objectives' => $this->objectives,
            'dependencies' => $this->dependencies,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
        ];
    }
}
