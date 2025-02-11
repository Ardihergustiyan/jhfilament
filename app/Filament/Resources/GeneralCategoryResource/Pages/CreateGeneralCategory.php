<?php

namespace App\Filament\Resources\GeneralCategoryResource\Pages;

use App\Filament\Resources\GeneralCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGeneralCategory extends CreateRecord
{
    protected static string $resource = GeneralCategoryResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'General Category created';
    }
}
