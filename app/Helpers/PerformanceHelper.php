<?php

/**
 * Performance Optimization Helpers
 * Helper functions untuk optimasi performa
 */

if (!function_exists('cache_result')) {
    /**
     * Cache query result dengan key yang di-generate otomatis
     *
     * @param string $key Cache key identifier
     * @param int $minutes Cache duration
     * @param callable $callback Query callback
     * @return mixed
     */
    function cache_result(string $key, int $minutes, callable $callback)
    {
        $cacheKey = 'perf:' . $key;
        $result = cache()->get($cacheKey);

        if ($result) {
            return $result;
        }

        $result = $callback();
        cache()->put($cacheKey, $result, now()->addMinutes($minutes));

        return $result;
    }
}

if (!function_exists('chunk_query')) {
    /**
     * Process large queries in chunks untuk mengurangi memory usage
     *
     * @param mixed $query Eloquent query builder
     * @param int $chunkSize Chunk size
     * @param callable $callback Process callback
     * @return void
     */
    function chunk_query($query, int $chunkSize, callable $callback): void
    {
        $query->chunk($chunkSize, function ($records) use ($callback) {
            foreach ($records as $record) {
                $callback($record);
            }

            // Force garbage collection
            if (config('performance.memory.gc_enabled')) {
                gc_collect_cycles();
            }
        });
    }
}

if (!function_exists('eager_load')) {
    /**
     * Helper untuk eager loading dengan default relations
     *
     * @param string $modelType Model type identifier
     * @return array Default relations untuk model
     */
    function eager_load(string $modelType): array
    {
        return config('performance.eager_loading.default_relations')[$modelType] ?? [];
    }
}

if (!function_exists('cache_forever')) {
    /**
     * Cache result selamanya atau sampai di-clear
     *
     * @param string $key Cache key
     * @param callable $callback
     * @return mixed
     */
    function cache_forever(string $key, callable $callback)
    {
        $cacheKey = 'perf:forever:' . $key;
        $result = cache()->get($cacheKey);

        if ($result) {
            return $result;
        }

        $result = $callback();
        cache()->forever($cacheKey, $result);

        return $result;
    }
}

if (!function_exists('get_with_cache')) {
    /**
     * Get model dengan eager loading dan caching
     *
     * @param string $modelClass Model class
     * @param mixed $id Model ID
     * @param int $minutes Cache minutes
     * @return mixed
     */
    function get_with_cache(string $modelClass, $id, int $minutes = 60)
    {
        return cache_result(
            'model:' . class_basename($modelClass) . ':' . $id,
            $minutes,
            function () use ($modelClass, $id) {
                $relations = eager_load(strtolower(class_basename($modelClass) . 's'));

                if (empty($relations)) {
                    return $modelClass::find($id);
                }

                return $modelClass::with($relations)->find($id);
            }
        );
    }
}

if (!function_exists('invalidate_cache')) {
    /**
     * Invalidate specific cache pattern
     *
     * @param string $pattern Cache pattern (e.g., 'model:product:*')
     * @return void
     */
    function invalidate_cache(string $pattern): void
    {
        // Untuk file cache
        if (config('cache.default') === 'file') {
            $cachePath = storage_path('framework/cache/data');
            $files = glob($cachePath . '/perf:' . $pattern);

            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }

        // Untuk redis
        if (config('cache.default') === 'redis') {
            $keys = cache()->getRedis()->keys('perf:' . $pattern);

            foreach ($keys as $key) {
                cache()->forget($key);
            }
        }
    }
}

if (!function_exists('measure_performance')) {
    /**
     * Measure execution time dan memory usage
     *
     * @param string $label Label untuk measurement
     * @param callable $callback Code untuk di-measure
     * @return mixed Return value dari callback
     */
    function measure_performance(string $label, callable $callback)
    {
        $start = microtime(true);
        $memoryStart = memory_get_usage();

        $result = $callback();

        $time = (microtime(true) - $start) * 1000; // Convert to ms
        $memory = (memory_get_usage() - $memoryStart) / 1024; // KB

        if (config('performance.monitoring.log_execution_time')) {
            logger()->info("Performance: {$label} - Time: {$time}ms, Memory: {$memory}KB");
        }

        return $result;
    }
}

if (!function_exists('should_log_query')) {
    /**
     * Check jika query harus di-log berdasarkan execution time
     *
     * @param float $executionTime Query execution time in ms
     * @return bool
     */
    function should_log_query(float $executionTime): bool
    {
        if (!config('performance.monitoring.log_slow_queries')) {
            return false;
        }

        return $executionTime > config('performance.monitoring.slow_query_threshold', 1000);
    }
}
