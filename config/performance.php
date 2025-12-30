<?php

/**
 * Performance Optimization Configuration
 * Mengaktifkan berbagai strategi caching dan optimasi query
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi caching untuk performa optimal
    |
    */

    'cache' => [
        // Aktifkan response caching untuk halaman statis
        'enabled' => env('RESPONSE_CACHE_ENABLED', true),

        // Durasi cache dalam detik (7 hari)
        'lifetime' => env('RESPONSE_CACHE_LIFETIME', 604800),

        // Cache driver (gunakan Redis untuk performa lebih baik jika tersedia)
        'driver' => env('RESPONSE_CACHE_DRIVER', 'redis'),

        // Tag untuk mudah flush cache
        'tag' => 'lokallook_response_cache',
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Query Optimization
    |--------------------------------------------------------------------------
    |
    | Optimasi untuk query database
    |
    */

    'database' => [
        // Batch size untuk chunk() operations
        'batch_size' => 1000,

        // Enable query caching untuk model yang sering diakses
        'query_cache_enabled' => true,

        // Query cache duration dalam menit
        'query_cache_duration' => 60,

        // Cache pagination results
        'cache_pagination' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Optimization
    |--------------------------------------------------------------------------
    |
    | Optimasi untuk asset loading (CSS, JS, Images)
    |
    */

    'assets' => [
        // Minify CSS dan JS
        'minify' => true,

        // Enable critical CSS inline
        'critical_css' => true,

        // Lazy load images
        'lazy_load_images' => true,

        // Asset versioning untuk cache busting
        'versioning' => true,

        // Preload penting assets
        'preload_critical' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Memory & Garbage Collection
    |--------------------------------------------------------------------------
    |
    | Optimasi memory usage
    |
    */

    'memory' => [
        // Force garbage collection
        'gc_enabled' => true,

        // GC probability (dalam persen)
        'gc_probability' => 5,

        // Chunk queries untuk mengurangi memory usage
        'chunk_queries' => true,

        // Chunk size
        'chunk_size' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Optimization
    |--------------------------------------------------------------------------
    |
    | Hindari N+1 queries dengan eager loading
    |
    */

    'eager_loading' => [
        // Aktifkan lazy loading detection
        'prevent_lazy_loading' => env('APP_ENV') === 'local',

        // Log lazy loaded queries
        'log_lazy_loading' => true,

        // Default relations untuk eager load
        'default_relations' => [
            'products' => ['category', 'images', 'attributes'],
            'categories' => ['parent', 'children'],
            'orders' => ['customer', 'items'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Caching Headers
    |--------------------------------------------------------------------------
    |
    | Set cache headers untuk browser caching
    |
    */

    'http_caching' => [
        // Max age untuk public cache (dalam detik)
        'max_age' => 86400, // 1 hari

        // Enable ETag headers
        'etag' => true,

        // Enable Last-Modified headers
        'last_modified' => true,

        // Immutable assets cache time (dalam detik)
        'immutable_max_age' => 31536000, // 1 tahun
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring
    |--------------------------------------------------------------------------
    |
    | Monitor performa aplikasi
    |
    */

    'monitoring' => [
        // Aktifkan slow query logging
        'log_slow_queries' => true,

        // Slow query threshold dalam ms
        'slow_query_threshold' => 1000,

        // Log memory usage
        'log_memory_usage' => true,

        // Log execution time
        'log_execution_time' => true,
    ],
];
