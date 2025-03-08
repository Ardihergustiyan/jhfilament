<?php
// TotalPendapatan.php
namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TotalPendapatan extends BaseWidget
{
    use InteractsWithPageFilters; // Memastikan widget ini dapat berinteraksi dengan filter halaman

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Ambil filter dari dashboard
        $filters = $this->filters;

        // Query untuk mengambil data order yang selesai
        $query = Order::query()
            ->where('status_id', 3); // Ganti dengan status_id yang sesuai

        // Terapkan filter tanggal jika ada
        $query->when($filters['created_at'] ?? null, function ($query, $createdAt) {
            return $query->whereDate('created_at', '>=', Carbon::parse($createdAt));
        })
            ->when($filters['updated_at'] ?? null, function ($query, $updatedAt) {
                return $query->whereDate('updated_at', '<=', Carbon::parse($updatedAt));
            });

        // Hitung total pendapatan saat ini
        $totalPendapatan = $query->sum('total_price');

        // Hitung jumlah order yang selesai saat ini
        $orderCount = $query->count();

        // Mendapatkan total pendapatan bulan lalu
        $lastMonthQuery = $query->clone()->whereMonth('created_at', Carbon::now()->subMonth()->month);
        $lastMonthPendapatan = $lastMonthQuery->sum('total_price');

        // Mendapatkan jumlah order selesai bulan lalu
        $lastMonthOrderCount = $lastMonthQuery->count();

        // Menghitung perubahan pendapatan
        $change = $totalPendapatan - $lastMonthPendapatan;
        $isUp = $change > 0; // Cek apakah pendapatan naik (lebih besar dari bulan lalu)

        // Keterangan perubahan pendapatan
        $description = $isUp
            ? Number::currency($change, 'IDR') . ' increase'
            : Number::currency(abs($change), 'IDR') . ' decrease';

        // Tentukan icon berdasarkan arah perubahan pendapatan
        $descriptionIcon = $isUp ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        // Keterangan untuk jumlah order selesai
        $orderDescription = $orderCount - $lastMonthOrderCount;
        $isOrderUp = $orderDescription > 0; // Cek apakah jumlah order naik

        // Keterangan perubahan order selesai
        $orderDescriptionText = $isOrderUp
            ? $orderDescription . ' increase'
            : abs($orderDescription) . ' decrease';

        // Tentukan icon berdasarkan arah perubahan order
        $orderDescriptionIcon = $isOrderUp ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        // === Tambahan: Order Diproses ===
        $orderDiprosesQuery = Order::query()->whereIn('status_id', [1, 2]);

        // Terapkan filter tanggal jika ada
        $orderDiprosesQuery->when($filters['created_at'] ?? null, function ($query, $createdAt) {
            return $query->whereDate('created_at', '>=', Carbon::parse($createdAt));
        })
            ->when($filters['updated_at'] ?? null, function ($query, $updatedAt) {
                return $query->whereDate('updated_at', '<=', Carbon::parse($updatedAt));
            });

        // Hitung jumlah order diproses saat ini
        $orderDiprosesCount = $orderDiprosesQuery->count();

        // Hitung jumlah order diproses bulan lalu
        $lastMonthOrderDiprosesCount = $orderDiprosesQuery->clone()->whereMonth('created_at', Carbon::now()->subMonth()->month)->count();

        // Perbedaan order diproses
        $orderDiprosesChange = $orderDiprosesCount - $lastMonthOrderDiprosesCount;
        $isOrderDiprosesUp = $orderDiprosesChange > 0;

        // Keterangan perubahan order diproses
        $orderDiprosesDescription = $isOrderDiprosesUp
            ? $orderDiprosesChange . ' increase'
            : abs($orderDiprosesChange) . ' decrease';

        // Tentukan ikon berdasarkan arah perubahan order diproses
        $orderDiprosesIcon = $isOrderDiprosesUp ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        return [
            Stat::make('Total Pendapatan', Number::currency($totalPendapatan, 'IDR'))
                ->description($description)
                ->descriptionIcon($descriptionIcon)
                ->color($isUp ? 'success' : 'danger'),

            Stat::make('Order Selesai', $orderCount)
                ->description($orderDescriptionText)
                ->descriptionIcon($orderDescriptionIcon)
                ->color($isOrderUp ? 'success' : 'danger'),

            Stat::make('Order Diproses', $orderDiprosesCount)
                ->description($orderDiprosesDescription)
                ->descriptionIcon($orderDiprosesIcon)
                ->color($isOrderDiprosesUp ? 'success' : 'danger'),
        ];
    }
}

