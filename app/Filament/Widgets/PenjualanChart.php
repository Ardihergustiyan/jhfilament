<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class PenjualanChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Chart Penjualan';

    protected function getData(): array
    {
        // Ambil filter dari dashboard
        $filters = $this->filters;

        // Query untuk mengambil data order yang selesai
        $orders = Order::query()
            ->where('status_id', 3) // Ganti dengan status_id yang sesuai
            ->when($filters['created_at'] ?? null, function ($query, $createdAt) {
                return $query->whereDate('created_at', '>=', Carbon::parse($createdAt));
            })
            ->when($filters['updated_at'] ?? null, function ($query, $updatedAt) {
                return $query->whereDate('updated_at', '<=', Carbon::parse($updatedAt));
            })
            ->get();

        // Hitung jumlah order per bulan
        $orderCounts = $orders->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->format('M');
        })->map->count();

        // Data untuk chart
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = array_fill_keys($labels, 0);

        foreach ($orderCounts as $month => $count) {
            $data[$month] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orderan Selesai',
                    'data' => array_values($data),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}