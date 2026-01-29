<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Pagination\Paginator::useTailwind();

        // Share application settings across all views
        try {
            $settings = \App\Models\AppSetting::first() ?? new \App\Models\AppSetting(['app_name' => 'LITERASIA']);
            \Illuminate\Support\Facades\View::share('app_settings', $settings);
        } catch (\Exception $e) {
            // Fallback for when migrations haven't run yet or other issues
            \Illuminate\Support\Facades\View::share('app_settings', (object)[
                'app_name' => 'LITERASIA',
                'logo_url' => null,
                'app_logo' => null
            ]);
        }
    }
}
