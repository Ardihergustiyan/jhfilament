<?php

namespace App\Filament\Resources\ResellerLevelResource\Pages;

use App\Filament\Resources\ResellerLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResellerLevels extends ListRecords
{
    protected static string $resource = ResellerLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTitle(): string
    {
        return 'Level Reseller';
    }
}
