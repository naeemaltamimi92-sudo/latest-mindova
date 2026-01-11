<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerSkillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'volunteer_id' => $this->volunteer_id,
            'skill_name' => $this->skill_name,
            'skill_category' => $this->skill_category,
            'proficiency_level' => $this->proficiency_level,
            'years_of_experience' => $this->years_of_experience,
            'is_ai_extracted' => $this->is_ai_extracted,
            'source' => $this->source,
            'created_at' => $this->created_at,
        ];
    }
}
