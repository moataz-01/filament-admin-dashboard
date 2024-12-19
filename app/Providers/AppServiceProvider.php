<?php

namespace App\Providers;

use App\Models\Admin;
use BezhanSalleh\FilamentExceptions\Models\Exception;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseSizeCheck;
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * This method is used to bind interfaces to concrete implementations in the service container.
     * In this case, it binds the Authenticatable interface to the Admin model.
     */
    public function register(): void
    {
        // Bind the Authenticatable interface to the Admin model. This tells Laravel to use the Admin model
        // when an Authenticatable instance is needed (e.g., for authentication).
        $this->app->bind(Authenticatable::class, Admin::class);
    }

    /**
     * Bootstrap any application services.
     * This method is called after all service providers have been registered. It's used to perform
     * tasks like registering routes, event listeners, and other initialization logic.
     */
    public function boot(): void
    {
        // Define a gate before callback. This allows a super_admin to bypass all authorization checks.
        // A Gate::before callback is executed before any other authorization checks.
        Gate::before(function ($user, $ability) {
            // If the user has the 'super_admin' role, grant them access to everything.
            return $user->hasRole('super_admin') ? true : null; // Return null to continue with other checks if not super_admin
        });

        // Configure the Filament Language Switch.
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            // Get available locales from config
            $locales = config('app.available_locales', ['en']); // Default to English if not set
            $switch->locales($locales);
        });

        // Configure Spatie Health checks
        $enabledChecks = collect(config('health-checks.enabled_checks', []))
            ->filter(fn($isEnabled) => $isEnabled)
            ->keys()
            ->map(fn($checkName) => 'Spatie\Health\Checks\Checks\\' . $checkName)
            ->map(fn($checkClass) => new $checkClass)
            ->toArray();

        Health::checks($enabledChecks);

        // Register a policy for the Filament Exceptions package. This defines authorization rules for exceptions.
        Gate::policy(Exception::class, \App\Policies\ExceptionPolicy::class);
    }
}
