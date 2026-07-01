<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Challenge */
class CompanyDashboardChallengeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'description' => str($this->refined_brief ?? $this->original_description)->limit(120)->toString(),
            'created_at' => $this->created_at,
            'task_count' => $this->tasks()->count(),
        ];
    }
}
