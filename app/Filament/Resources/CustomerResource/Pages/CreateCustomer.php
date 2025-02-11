<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     // Assign role 'reseller' during creation
    //     $data['password'] = bcrypt('password'); // Set default password
    //     return $data;
    // }

    // protected function afterCreate(): void
    // {
    //     $this->record->assignRole('Customer');
    // }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Customer created';
    }
}
