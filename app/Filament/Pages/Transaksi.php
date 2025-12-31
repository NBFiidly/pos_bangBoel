<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\TransactionStatsWidget;
use App\Filament\Widgets\MonthlyTransactionsTableWidget;
use Filament\Pages\Page;

class Transaksi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.transaksi';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $title = 'Transaksi';

    protected function getHeaderWidgets(): array
    {
        return [
            TransactionStatsWidget::class,
            MonthlyTransactionsTableWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 12;
    }
}
