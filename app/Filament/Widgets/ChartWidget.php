<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget as BaseChartWidget;
use Illuminate\Support\Facades\DB;

class ChartWidget extends BaseChartWidget
{
    public int | string | array $columnSpan = 12;
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Orders per Month';

    protected function getData(): array
    {
        $data = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $chartData = array_fill(0, 12, 0);

        foreach ($data as $item) {
            $chartData[$item->month - 1] = $item->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $chartData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
