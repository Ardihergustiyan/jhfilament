<?php

namespace App\Filament\Resources\GeneralCategoryResource\Pages;

use App\Filament\Resources\GeneralCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeneralCategory extends EditRecord
{
    protected static string $resource = GeneralCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getSavedNotificationTitle(): ?string
    {
        return 'General Category update';
    }
}
