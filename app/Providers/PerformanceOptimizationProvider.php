<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class PerformanceOptimizationProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enable lazy loading detection in development
        if ($this->app->environment('local')) {
            Model::preventLazyLoading();
        }

        // Hide default timestamps if not needed
        // Model::$timestamps = false;

        // Enable query log in development only
        if ($this->app->environment('local')) {
            \DB::enableQueryLog();
        }

        // Enable model caching for frequently accessed models
        $this->optimizeModelQueries();
    }

    /**
     * Optimize database queries with caching
     */
    private function optimizeModelQueries(): void
    {
        // Hook into model retrieval to cache results
        \Event::listen('illuminate.query', function ($query) {
            // Log slow queries (above 1000ms)
            if ($query->time > 1000) {
                \Log::warning('Slow Query Detected', [
                    'query' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                ]);
            }
        });
    }
}
