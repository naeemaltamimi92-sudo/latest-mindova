<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your OpenAI API credentials and default settings.
    |
    */

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'timeout' => env('OPENAI_TIMEOUT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Models Configuration
    |--------------------------------------------------------------------------
    |
    | Define which OpenAI model to use for each operation.
    | gpt-4o: Most capable, higher cost
    | gpt-4o-mini: Fast and cost-effective for simpler tasks
    |
    */

    'models' => [
        'cv_analysis' => env('AI_MODEL_CV_ANALYSIS', 'gpt-4o'),
        'challenge_analysis' => env('AI_MODEL_CHALLENGE_ANALYSIS', 'gpt-4o'),
        'task_generation' => env('AI_MODEL_TASK_GENERATION', 'gpt-4o'),
        'volunteer_matching' => env('AI_MODEL_VOLUNTEER_MATCHING', 'gpt-4o-mini'),
        'idea_scoring' => env('AI_MODEL_IDEA_SCORING', 'gpt-4o-mini'),
        'comment_analysis' => env('AI_MODEL_COMMENT_ANALYSIS', 'gpt-4o'),
        'solution_analysis' => env('AI_MODEL_SOLUTION_ANALYSIS', 'gpt-4o'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Confidence Thresholds
    |--------------------------------------------------------------------------
    |
    | Minimum confidence scores (0-100) required for AI outputs to be
    | automatically accepted. Below this threshold, items are flagged
    | for human review.
    |
    */

    'confidence_threshold' => [
        'cv_analysis' => env('AI_CONFIDENCE_CV_ANALYSIS', 70.0),
        'challenge_analysis' => env('AI_CONFIDENCE_CHALLENGE_ANALYSIS', 75.0),
        'task_generation' => env('AI_CONFIDENCE_TASK_GENERATION', 80.0),
        'volunteer_matching' => env('AI_CONFIDENCE_VOLUNTEER_MATCHING', 65.0),
        'idea_scoring' => env('AI_CONFIDENCE_IDEA_SCORING', 60.0),
    ],

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Pricing
    |--------------------------------------------------------------------------
    |
    | Pricing per 1M tokens for cost tracking and estimation.
    | Update these values if OpenAI changes pricing.
    |
    */

    'pricing' => [
        'gpt-4o' => [
            'input' => 2.50,  // USD per 1M input tokens
            'output' => 10.00, // USD per 1M output tokens
        ],
        'gpt-4o-mini' => [
            'input' => 0.150,  // USD per 1M input tokens
            'output' => 0.600, // USD per 1M output tokens
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Configure retry behavior for failed AI requests.
    |
    */

    'retry' => [
        'max_attempts' => env('AI_RETRY_MAX_ATTEMPTS', 3),
        'delay_ms' => env('AI_RETRY_DELAY_MS', 1000),
        'backoff_multiplier' => env('AI_RETRY_BACKOFF_MULTIPLIER', 2),
    ],

];
