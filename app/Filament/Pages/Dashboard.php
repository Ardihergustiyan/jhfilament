<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\TotalPendapatan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;
    // protected static ?int $navigationSort = 2;
    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            // Add your form fields here
            Section::make("Filter")->schema([
                DatePicker::make("created_at")
                    ->native(false)
                    ->label("Dari Tanggal")
                    ->placeholder("Filter by created date"),
                DatePicker::make("updated_at")
                    ->label("Sampai Tanggal")
                    ->native(false)
                    ->placeholder("Filter by updated date"),
            ])
                ->columns(2)
                ->columnSpan(2),

        ]);
        ;


    }


}