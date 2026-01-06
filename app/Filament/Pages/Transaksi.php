<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\TransactionStatsWidget;
use App\Filament\Widgets\MonthlyTransactionsTableWidget;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;

class Transaksi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string $view = 'filament.pages.transaksi';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $title = 'Transaksi';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportPDF')
                ->label('Export PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->form([
                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->displayFormat('d/m/Y')
                        ->native(false),
                    DatePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->displayFormat('d/m/Y')
                        ->native(false),
                ])
                ->action(function (array $data) {
                    $url = route('invoice.export-pdf', [
                        'start_date' => $data['start_date'] ?? null,
                        'end_date' => $data['end_date'] ?? null,
                    ]);
                    
                    return redirect($url);
                }),
        ];
    }

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
