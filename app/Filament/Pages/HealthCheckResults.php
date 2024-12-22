<?php

namespace App\Filament\Pages;

use ShuvroRoy\FilamentSpatieLaravelHealth\Pages\HealthCheckResults as BaseHealthCheckResults;

class HealthCheckResults extends BaseHealthCheckResults
{
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('page_HealthCheckResults');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('general.settings');
    }
}
