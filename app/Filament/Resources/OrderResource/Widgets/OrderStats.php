<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Order Diproses', Order::query()->whereIn('status_id', [1, 2])->count()),
            Stat::make('Order Selesai', Order::query()->where('status_id', '3')->count()),
            // Stat::make('Total Pendapatan', Number::currency(Order::query()->avg('total_price'), 'IDR')),
            Stat::make('Total Pendapatan', Number::currency(
                Order::query()
                    ->where('status_id', '=', 3) 
                    ->sum('total_price'),
                'IDR'
            )),
        ];
    }
}
