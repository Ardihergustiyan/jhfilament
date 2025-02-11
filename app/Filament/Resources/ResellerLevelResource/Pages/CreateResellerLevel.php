<?php

namespace App\Filament\Resources\ResellerLevelResource\Pages;

use App\Filament\Resources\ResellerLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateResellerLevel extends CreateRecord
{
    protected static string $resource = ResellerLevelResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Reseller Level created';
    }
}
