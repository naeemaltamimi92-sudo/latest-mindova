<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'title' => $this->title,
            'original_description' => $this->original_description,
            'refined_brief' => $this->refined_brief,
            'complexity_level' => $this->complexity_level,
            'challenge_type' => $this->challenge_type,
            'status' => $this->status,
            'submission_deadline' => $this->submission_deadline,
            'completion_deadline' => $this->completion_deadline,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'challenge_analyses' => ChallengeAnalysisResource::collection($this->whenLoaded('challengeAnalyses')),
            'workstreams' => WorkstreamResource::collection($this->whenLoaded('workstreams')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'ideas' => IdeaResource::collection($this->whenLoaded('ideas')),
        ];
    }
}
