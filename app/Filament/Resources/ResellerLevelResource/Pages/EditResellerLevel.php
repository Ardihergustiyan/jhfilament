<?php

namespace App\Filament\Resources\ResellerLevelResource\Pages;

use App\Filament\Resources\ResellerLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResellerLevel extends EditRecord
{
    protected static string $resource = ResellerLevelResource::class;

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
        return 'Reseller Level update';
    }
}
