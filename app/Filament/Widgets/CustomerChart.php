<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class CustomerChart extends ChartWidget
{
    use InteractsWithPageFilters; // Menggunakan filter dari Dashboard

    protected static ?string $heading = 'Customer Growth';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $filters = $this->filters;

        // Query user dengan peran 'customer' dan filter berdasarkan tanggal
        $customers = User::role('customer')
            ->when($filters['created_at'] ?? null, function ($query, $createdAt) {
                return $query->whereDate('created_at', '>=', Carbon::parse($createdAt));
            })
            ->when($filters['updated_at'] ?? null, function ($query, $updatedAt) {
                return $query->whereDate('created_at', '<=', Carbon::parse($updatedAt));
            }, function ($query) { // Jika filter kosong, default ke tahun ini
                return $query->whereYear('created_at', Carbon::now()->year);
            })
            ->get();

        // Hitung jumlah customer per bulan
        $customerCounts = $customers->groupBy(function ($customer) {
            return Carbon::parse($customer->created_at)->format('M');
        })->map->count();

        // Label bulan
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = array_fill_keys($labels, 0); // Isi default 0

        foreach ($customerCounts as $month => $count) {
            $data[$month] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Customers Joined',
                    'data' => array_values($data),

                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}