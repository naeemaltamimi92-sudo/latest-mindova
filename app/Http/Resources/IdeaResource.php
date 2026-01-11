<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdeaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'challenge_id' => $this->challenge_id,
            'volunteer_id' => $this->volunteer_id,
            'title' => $this->title,
            'description' => $this->description,
            'ai_score' => $this->ai_score,
            'ai_feedback' => $this->ai_feedback,
            'community_votes' => $this->community_votes,
            'final_score' => $this->final_score,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'volunteer' => new VolunteerResource($this->whenLoaded('volunteer')),
            'challenge' => new ChallengeResource($this->whenLoaded('challenge')),
        ];
    }
}
