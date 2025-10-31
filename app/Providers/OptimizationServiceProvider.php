<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class OptimizationServiceProvider extends ServiceProvider
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
        // Log slow queries in development
        if (config('app.debug') && config('app.env') !== 'production') {
            DB::listen(function ($query) {
                if ($query->time > 1000) { // Queries taking more than 1 second
                    Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms',
                    ]);
                }
            });
        }

        // Cache configuration for production
        if (config('app.env') === 'production') {
            // Clear cache on model updates
            $this->registerModelCacheClearing();
        }
    }

    /**
     * Register model cache clearing on updates
     */
    protected function registerModelCacheClearing(): void
    {
        // Clear cache when courses are updated
        \App\Models\Course::updated(function ($course) {
            Cache::forget('homepage.featured_courses');
            Cache::forget('homepage.featured_courses');
            Cache::tags(['courses'])->flush(); // If using cache tags
        });

        // Clear cache when testimonials are updated
        \App\Models\Testimonial::updated(function ($testimonial) {
            Cache::forget('homepage.featured_testimonials');
            Cache::forget('homepage.testimonials');
            Cache::forget('testimonials_filter_courses_' . md5('approved'));
        });

        // Clear cache when users are updated (affects statistics)
        \App\Models\User::created(function () {
            Cache::forget('homepage.stats');
            Cache::forget('stats.total_courses');
        });
    }
}

