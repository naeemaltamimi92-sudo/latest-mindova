<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'stage',
        'summary',
        'objectives',
        'constraints',
        'assumptions',
        'stakeholders',
        'missing_information',
        'success_criteria',
        'complexity_level',
        'complexity_justification',
        'risk_assessment',
        'confidence_score',
        'recommended_approach',
        'estimated_effort_hours',
        'validation_status',
        'validation_errors',
        'requires_human_review',
        'openai_model_used',
        'tokens_used',
    ];

    protected function casts(): array
    {
        return [
            'objectives' => 'array',
            'constraints' => 'array',
            'assumptions' => 'array',
            'stakeholders' => 'array',
            'missing_information' => 'array',
            'success_criteria' => 'array',
            'validation_errors' => 'array',
            'complexity_level' => 'integer',
            'confidence_score' => 'decimal:2',
            'estimated_effort_hours' => 'decimal:2',
            'requires_human_review' => 'boolean',
            'tokens_used' => 'integer',
        ];
    }

    /**
     * Get the challenge that this analysis belongs to.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Check if validation passed.
     */
    public function validationPassed(): bool
    {
        return $this->validation_status === 'passed';
    }
}
