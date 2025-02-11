<?php

namespace App\Filament\Resources\GeneralCategoryResource\Pages;

use App\Filament\Resources\GeneralCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneralCategories extends ListRecords
{
    protected static string $resource = GeneralCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kategori Umum'),
        ];
    }
    public function getTitle(): string
    {
        return 'Kategori Umum';
    }
}
