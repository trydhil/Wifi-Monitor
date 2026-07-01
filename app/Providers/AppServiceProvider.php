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
        try {
            if (class_exists(\App\Models\CustomScoringSetting::class) && \Illuminate\Support\Facades\Schema::hasTable('custom_scoring_settings')) {
                $customConfig = \App\Models\CustomScoringSetting::current()->toScoringConfig();
                config(['scoring.custom' => $customConfig]);
            }
        } catch (\Throwable $e) {
            // Cegah kegagalan booting saat database belum siap/dalam proses migrasi
        }
    }
}
