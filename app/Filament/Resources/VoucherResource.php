<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $pluralLabel = 'Voucher';
    
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Promosi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('code')
                                    ->label('Kode Voucher')
                                    ->required()
                                    ->unique(),
                                TextInput::make('discount_percentage')
                                    ->label('Diskon (%)')
                                    ->numeric()
                                    ->required(),
                                DateTimePicker::make('start_date')
                                    ->label('Mulai')
                                    ->required(),
                                DateTimePicker::make('end_date')
                                    ->label('Berakhir')
                                    ->required(),
                                Select::make('status')
                                    ->options([
                                        'Used' => 'Digunakan',
                                        'Unused' => 'Belum Digunakan',
                                    ])
                                    ->default('Belum Digunakan')
                                    ->required(),
                            ]),
                    ])
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Voucher')
                    ->searchable(),
                TextColumn::make('discount_percentage')
                    ->label('Diskon (%)'),
                TextColumn::make('status')
                    ->label('Status'),
                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->dateTime(),
                TextColumn::make('end_date')
                    ->label('Berakhir')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                   ViewAction::make(),
                   EditAction::make(),
                   DeleteAction::make(),
               ])
           ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
