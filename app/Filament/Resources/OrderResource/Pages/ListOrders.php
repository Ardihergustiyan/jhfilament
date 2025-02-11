<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    Protected function getHeaderWidgets(): array {
        return [
            OrderStats::class
        ];
    }
    public function getTitle(): string
    {
        return 'Daftar Pesanan';
    }

    public function getTabs(): array {
        return [
            null => Tab::make('All'),
            'diproses' => Tab::make()->query(fn($query) => $query->where('status_id', '1')),
            'siap diambil' => Tab::make()->query(fn($query) => $query->where('status_id', '2')),
            'selesai' => Tab::make()->query(fn($query) => $query->where('status_id', '3')),
            'dibatalkan' => Tab::make()->query(fn($query) => $query->where('status_id', '4')),
        ];  
    }
}
