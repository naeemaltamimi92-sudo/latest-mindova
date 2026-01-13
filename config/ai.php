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
    | Anthropic Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your Anthropic API credentials and default settings.
    |
    */

    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
        'base_url' => env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com'),
        'timeout' => env('ANTHROPIC_TIMEOUT', 120),
        'max_tokens' => env('ANTHROPIC_MAX_TOKENS', 4096),
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Models Configuration
    |--------------------------------------------------------------------------
    |
    | Define which model to use for each operation.
    | CV analysis uses Claude (Anthropic)
    | Other operations use OpenAI models
    |
    */

    'models' => [
        'cv_analysis' => env('AI_MODEL_CV_ANALYSIS', 'claude-sonnet-4-20250514'),
        'challenge_analysis' => env('AI_MODEL_CHALLENGE_ANALYSIS', 'claude-sonnet-4-20250514'),
        'task_generation' => env('AI_MODEL_TASK_GENERATION', 'claude-sonnet-4-20250514'),
        'volunteer_matching' => env('AI_MODEL_VOLUNTEER_MATCHING', 'claude-sonnet-4-20250514'),
        'idea_scoring' => env('AI_MODEL_IDEA_SCORING', 'claude-sonnet-4-20250514'),
        'team_formation' => env('AI_MODEL_TEAM_FORMATION', 'claude-sonnet-4-20250514'),
        'comment_analysis' => env('AI_MODEL_COMMENT_ANALYSIS', 'claude-sonnet-4-20250514'),
        'solution_analysis' => env('AI_MODEL_SOLUTION_ANALYSIS', 'claude-sonnet-4-20250514'),
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
        'claude-sonnet-4-20250514' => [
            'input' => 3.00,  // USD per 1M input tokens
            'output' => 15.00, // USD per 1M output tokens
        ],
        'claude-opus-4-20250514' => [
            'input' => 15.00,  // USD per 1M input tokens
            'output' => 75.00, // USD per 1M output tokens
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
