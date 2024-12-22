<?php

namespace App\Filament\Pages;

use Dotswan\FilamentLaravelPulse\Widgets\PulseCache;
use Dotswan\FilamentLaravelPulse\Widgets\PulseExceptions;
use Dotswan\FilamentLaravelPulse\Widgets\PulseQueues;
use Dotswan\FilamentLaravelPulse\Widgets\PulseServers;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowOutGoingRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowQueries;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseUsage;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;

class Insights extends Page
{
    use HasFiltersAction;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.insights';

    public static function getNavigationLabel(): string
    {
        return trans('general.insights');
    }
    public function getTitle(): string
    {
        return trans('general.insights');
    }
    public static function getNavigationGroup(): ?string
    {
        return trans('general.settings');
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can('view_insights');
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 12;
    }

    protected function getHeaderActions(): array
    {
        $currentPeriod = request()->get('period', '1h');

        return [
            ActionGroup::make([
                Action::make('1h')
                    ->action(fn() => $this->redirect(route('filament.admin.pages.insights')))
                    ->badge($currentPeriod === '1h' ? '✓' : null)
                    ->badgeColor('success'),
                Action::make('24h')
                    ->action(fn() => $this->redirect(route('filament.admin.pages.insights', ['period' => '24_hours'])))
                    ->badge($currentPeriod === '24_hours' ? '✓' : null)
                    ->badgeColor('success'),
                Action::make('7d')
                    ->action(fn() => $this->redirect(route('filament.admin.pages.insights', ['period' => '7_days'])))
                    ->badge($currentPeriod === '7_days' ? '✓' : null)
                    ->badgeColor('success'),
            ])
                ->label(__('Filter'))
                ->icon('heroicon-m-funnel')
                ->size(ActionSize::Small)
                ->color('gray')
                ->button()
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            PulseServers::class,
            PulseCache::class,
            PulseExceptions::class,
            PulseUsage::class,
            PulseQueues::class,
            PulseSlowQueries::class,
            PulseSlowRequests::class,
            PulseSlowOutGoingRequests::class
        ];
    }
}
