<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;
    protected static ?int $navigationSort = 2;
    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            // Add your form fields here
            Section::make("Filter")->schema([
                // TextInput::make("name"),
                DatePicker::make("created_at"),
                DatePicker::make("updated_at"),
            ])->columns(2),

        ]);
    }

}