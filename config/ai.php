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
        'timeout' => env('ANTHROPIC_TIMEOUT', 180),
        'max_tokens' => env('ANTHROPIC_MAX_TOKENS', 8192),
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
        'cv_analysis' => env('AI_MODEL_CV_ANALYSIS', 'claude-sonnet-4-6'),
        'challenge_analysis' => env('AI_MODEL_CHALLENGE_ANALYSIS', 'claude-sonnet-4-6'),
        'task_generation' => env('AI_MODEL_TASK_GENERATION', 'claude-sonnet-4-6'),
        'volunteer_matching' => env('AI_MODEL_VOLUNTEER_MATCHING', 'claude-sonnet-4-6'),
        'idea_scoring' => env('AI_MODEL_IDEA_SCORING', 'claude-sonnet-4-6'),
        'team_formation' => env('AI_MODEL_TEAM_FORMATION', 'claude-sonnet-4-6'),
        'comment_analysis' => env('AI_MODEL_COMMENT_ANALYSIS', 'claude-sonnet-4-6'),
        'solution_analysis' => env('AI_MODEL_SOLUTION_ANALYSIS', 'claude-sonnet-4-6'),
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
    | AI Request Log Retention
    |--------------------------------------------------------------------------
    |
    | openai_requests stores the full prompt/response text of every AI
    | call (can include CV contents and other PII). Rows older than this
    | many days are pruned by the ai-requests:prune scheduled command.
    |
    */

    'request_log_retention_days' => env('AI_REQUEST_LOG_RETENTION_DAYS', 90),

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
        'claude-sonnet-4-6' => [
            'input' => 3.00,  // USD per 1M input tokens
            'output' => 15.00, // USD per 1M output tokens
        ],
        'claude-opus-4-8' => [
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
