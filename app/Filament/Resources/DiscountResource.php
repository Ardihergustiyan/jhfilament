<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\RelationManagers;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $pluralLabel = 'Diskon';
    
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
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                TextInput::make('slug')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->required()
                                    ->dehydrated()
                                    ->unique(Discount::class, 'slug', ignoreRecord: true),

                                TextInput::make('discount_percentage')
                                    ->label('Diskon (%)')
                                    ->numeric()
                                    ->required(),

                                Select::make('applicable_to')
                                    ->label('Digunakan untuk')
                                    ->options([
                                        'Semua' => 'Semua Produk',
                                        'Category' => 'Kategori Tertentu',
                                        'Product' => 'Produk Tertentu',
                                    ])
                                    ->reactive()
                                    ->required(),
                                
                                Select::make('applicable_ids')
                                    ->label('Product/Category')
                                    ->options(function (callable $get) {
                                        $applicableTo = $get('applicable_to');
                                        if ($applicableTo === 'Product') {
                                            // Jika memilih "Produk Tertentu", tampilkan daftar produk
                                            return Product::all()->pluck('name', 'id');
                                        } elseif ($applicableTo === 'Category') {
                                            // Jika memilih "Kategori Tertentu", tampilkan daftar kategori
                                            return Category::all()->pluck('name', 'id');
                                        }
                                
                                        // Jika tidak, kembalikan array kosong
                                        return [];
                                    })
                                    ->multiple()
                                    ->hidden(fn (callable $get) => $get('applicable_to') === 'Semua'),

                                DateTimePicker::make('start_date')
                                    ->label('Mulai')
                                    ->required(),
                                DateTimePicker::make('end_date')
                                    ->label('Berakhir')
                                    ->required(),
                            ]),
                    ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('discount_percentage')
                    ->label('Diskon (%)'),
                TextColumn::make('applicable_to')
                    ->label('Digunakan untuk'),
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}
