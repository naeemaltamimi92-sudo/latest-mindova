<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeAnalysisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'challenge_id' => $this->challenge_id,
            'stage' => $this->stage,
            'objectives' => $this->objectives,
            'constraints' => $this->constraints,
            'success_criteria' => $this->success_criteria,
            'confidence_score' => $this->confidence_score,
            'validation_status' => $this->validation_status,
            'validation_errors' => $this->validation_errors,
            'requires_human_review' => $this->requires_human_review,
            'created_at' => $this->created_at,
        ];
    }
}
