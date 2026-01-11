<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'cv_file_path' => $this->cv_file_path,
            'availability_hours_per_week' => $this->availability_hours_per_week,
            'experience_level' => $this->experience_level,
            'bio' => $this->bio,
            'education' => $this->education,
            'work_experience' => $this->work_experience,
            'professional_domains' => $this->professional_domains,
            'years_of_experience' => $this->years_of_experience,
            'reputation_score' => $this->reputation_score,
            'ai_analysis_status' => $this->ai_analysis_status,
            'ai_analysis_confidence' => $this->ai_analysis_confidence,
            'ai_analysis_completed_at' => $this->ai_analysis_completed_at,
            'validation_status' => $this->validation_status,
            'skills_normalized' => $this->skills_normalized,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'skills' => VolunteerSkillResource::collection($this->whenLoaded('skills')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
