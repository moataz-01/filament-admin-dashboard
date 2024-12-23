<?php

namespace App\Providers\Filament;

use App\Filament\Pages\HealthCheckResults;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\SpatieLaravelTranslatablePlugin;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()
            ->colors($this->getColorScheme())
            ->brandLogo($this->loadBrandLogo())
            ->favicon($this->loadFavicon())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets($this->getWidgets())
            ->middleware($this->getMiddleware())
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins($this->loadPlugins())
            ->databaseNotifications()
            ->spa();
    }

    private function getColorScheme(): array
    {
        return [
            'danger' => Color::Rose,
            'gray' => Color::Gray,
            'info' => Color::Blue,
            'primary' => Color::Indigo,
            'success' => Color::Emerald,
            'warning' => Color::Orange,
        ];
    }

    private function getWidgets(): array
    {
        return [
            Widgets\AccountWidget::class,
            Widgets\FilamentInfoWidget::class,
        ];
    }

    private function getMiddleware(): array
    {
        return [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];
    }

    private function loadPlugins(): array
    {
        if (!$this->isDatabaseDeclared()) {
            return [];
        }

        return [
            \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            SpatieLaravelTranslatablePlugin::make()
                ->defaultLocales(array_keys(config('app.available_locales', ['en' => 'English']))),
            FilamentSpatieLaravelHealthPlugin::make()
                ->usingPage(HealthCheckResults::class),
            \TomatoPHP\FilamentSettingsHub\FilamentSettingsHubPlugin::make()
                ->allowSiteSettings()
                ->allowSocialMenuSettings()
                ->allowShield(),
            EnvironmentIndicatorPlugin::make(),
        ];
    }

    private function loadBrandLogo(): string
    {
        return $this->loadSetting('site_logo');
    }

    private function loadFavicon(): string
    {
        return $this->loadSetting('site_profile');
    }

    private function loadSetting(string $key): string
    {
        if ($this->isDatabaseDeclared() && Schema::hasTable('settings')) {
            return asset('media/' . setting($key));
        }
        return '';
    }

    private function isDatabaseDeclared(): bool
    {
        return Env::get('DB_DATABASE') !== null;
    }
}
