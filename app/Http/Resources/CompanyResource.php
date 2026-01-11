<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'company_name' => $this->company_name,
            'industry' => $this->industry,
            'website' => $this->website,
            'description' => $this->description,
            'logo_path' => $this->logo_path,
            'total_challenges_submitted' => $this->total_challenges_submitted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'challenges' => ChallengeResource::collection($this->whenLoaded('challenges')),
        ];
    }
}
