<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsWidget;
use App\Filament\Widgets\ChartWidget;
use App\Filament\Widgets\DailyOrdersTableWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }

    public function getWidgets(): array
    {
        return [
            StatsWidget::class,
            ChartWidget::class,
            DailyOrdersTableWidget::class,
        ];
    }

    public function getColumns(): int | string
    {
        return 12;
    }
}
