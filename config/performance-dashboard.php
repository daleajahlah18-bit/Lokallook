<?php

/**
 * Performance Dashboard Configuration
 * Untuk monitoring dan tracking performance metrics
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Dashboard Settings
    |--------------------------------------------------------------------------
    */

    'enabled' => env('PERFORMANCE_DASHBOARD_ENABLED', true),

    'path' => env('PERFORMANCE_DASHBOARD_PATH', '/admin/performance'),

    /*
    |--------------------------------------------------------------------------
    | Metrics Collection
    |--------------------------------------------------------------------------
    */

    'metrics' => [
        'response_time' => [
            'enabled' => true,
            'thresholds' => [
                'excellent' => 200,      // < 200ms
                'good' => 500,           // < 500ms
                'warning' => 1000,       // < 1000ms
                'critical' => 5000,      // > 1000ms
            ],
        ],

        'memory_usage' => [
            'enabled' => true,
            'thresholds' => [
                'excellent' => 32,       // < 32MB
                'good' => 64,            // < 64MB
                'warning' => 128,        // < 128MB
                'critical' => 256,       // > 128MB
            ],
        ],

        'database_queries' => [
            'enabled' => true,
            'max_queries_warning' => 20,     // Warn if > 20 queries
            'max_queries_critical' => 50,    // Alert if > 50 queries
            'slow_query_threshold' => 1000,  // ms
        ],

        'cache_hit_rate' => [
            'enabled' => true,
            'target_rate' => 75,  // Target 75% hit rate
        ],

        'page_load_time' => [
            'enabled' => true,
            'thresholds' => [
                'excellent' => 1000,     // < 1s
                'good' => 2000,          // < 2s
                'warning' => 3000,       // < 3s
                'critical' => 5000,      // > 3s
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Retention
    |--------------------------------------------------------------------------
    */

    'retention' => [
        'hourly' => 24,          // Keep hourly data for 24 hours
        'daily' => 30,           // Keep daily data for 30 days
        'monthly' => 12,         // Keep monthly data for 12 months
    ],

    /*
    |--------------------------------------------------------------------------
    | Alerts Configuration
    |--------------------------------------------------------------------------
    */

    'alerts' => [
        'enabled' => true,

        'channels' => [
            'mail' => env('PERFORMANCE_ALERT_MAIL', false),
            'slack' => env('PERFORMANCE_ALERT_SLACK', false),
            'log' => env('PERFORMANCE_ALERT_LOG', true),
        ],

        'recipients' => [
            'email' => env('PERFORMANCE_ALERT_EMAIL', 'admin@example.com'),
            'slack_webhook' => env('SLACK_WEBHOOK_URL', ''),
        ],

        'conditions' => [
            'response_time_critical' => true,
            'memory_usage_critical' => true,
            'slow_queries' => true,
            'cache_hit_rate_low' => true,
            'error_rate_high' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Budgets
    |--------------------------------------------------------------------------
    */

    'budgets' => [
        'enabled' => true,

        'items' => [
            'total_bundle_size' => [
                'limit' => 250,      // KB
                'warning' => 200,
            ],
            'js_bundle_size' => [
                'limit' => 150,      // KB
                'warning' => 120,
            ],
            'css_bundle_size' => [
                'limit' => 50,       // KB
                'warning' => 40,
            ],
            'database_queries' => [
                'limit' => 30,
                'warning' => 20,
            ],
            'response_time' => [
                'limit' => 500,      // ms
                'warning' => 300,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes to Monitor
    |--------------------------------------------------------------------------
    */

    'monitored_routes' => [
        'shop.home.index',
        'shop.product_or_category.index',
        'shop.search.index',
        'admin.dashboard',
    ],

    /*
    |--------------------------------------------------------------------------
    | Comparison Baseline
    |--------------------------------------------------------------------------
    */

    'baseline' => [
        // Performance metrics sebelum optimasi
        'response_time' => 800,      // ms
        'memory_usage' => 128,       // MB
        'database_queries' => 50,
        'bundle_size' => 500,        // KB
        'page_load_time' => 8000,    // ms
    ],

    /*
    |--------------------------------------------------------------------------
    | Goals (Target setelah optimasi)
    |--------------------------------------------------------------------------
    */

    'goals' => [
        'response_time' => 150,      // ms (75% improvement)
        'memory_usage' => 64,        // MB (50% improvement)
        'database_queries' => 8,     // (84% reduction)
        'bundle_size' => 180,        // KB (64% reduction)
        'page_load_time' => 1500,    // ms (81% improvement)
        'cache_hit_rate' => 75,      // % (dari 0%)
    ],
];
