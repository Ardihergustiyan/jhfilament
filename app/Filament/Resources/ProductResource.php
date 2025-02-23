<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use Filament\Tables\Columns\HtmlColumn;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\ReviewsRelationManager;
use Filament\Forms\Components\Repeater;
use App\Models\Product;
use Faker\Core\File;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $pluralLabel = 'Produk';

    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Product Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Informasi Produk')->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'slug')
                            ->required(),
                    
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->live(onBlur: true)
                            ->maxLength(255)->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->maxLength(255)
                            ->disabled()
                            ->required()
                            ->dehydrated()
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        TextInput::make('stock')
                            ->label('Stock')
                            ->numeric()
                            ->required(),

                        // Forms\Components\Textarea::make('description')
                        //     ->label('Description')
                        //     ->maxLength(65535)
                        //     ->required(),

                        MarkdownEditor::make('description')
                            ->label('Deskrpsi Produk')
                            ->required()
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products')
                    ])->columns(2),

                    Section::make('Gambar')->schema([
                        FileUpload::make('image')
                            ->label('Masukkan Gambar Produk')
                            ->multiple()
                            ->directory('products')
                            ->maxFiles(5)
                            ->reorderable()
                    ]),

                    Section::make('Produk Variant')->schema([
                        Repeater::make('productVariant')
                        ->label('Product Variants')
                        ->relationship()
                        ->schema([
                            TextInput::make('color')
                                ->label('Warna Variant')
                                ->required(),
                            FileUpload::make('image')
                                ->label('Masukkan Gambar Produk')
                                ->multiple()
                                ->directory('products')
                                ->maxFiles(5)
                                ->reorderable(),
                            TextInput::make('stock')
                                ->label('Stock')
                                ->numeric()
                                ->required(),
                        ]),
                    ]),
                ])->columnSpan(2),
                
                Group::make()->schema([
                    Section::make('Harga')->schema([
                        
                        Forms\Components\TextInput::make('base_price')
                            ->label('Harga Normal')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->required()
                            ->prefix('IDR'),
                        Forms\Components\TextInput::make('het_price')
                            ->label('Harga Eceran Tertinggi')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->required()
                            ->prefix('IDR'),
                        
                        Repeater::make('productPrices')
                            ->label('Harga sesuai level reseller')
                            ->relationship()
                            ->schema([
                                Select::make('reseller_level_id')
                                    ->label('Level resseler')
                                    ->relationship('resellerLevel', 'name') 
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required(),
                                TextInput::make('price')
                                    ->label('Harga')
                                    ->required()
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->numeric()
                                    ->prefix('IDR'),
                            ])
                            ]),
                            section::make('link Shopee / Lazada')->schema([
                                Repeater::make('external_product')
                                    ->label('masukkan link')
                                    ->schema([
                                        TextInput::make('external_product')
                                            ->label('Link')
                                            ->url()
                                            ->required()
                                            ->placeholder('Masukkan link produk'),
                                ]),
                            ])  
            ])->columnSpan(1),
            ])->columns(3);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->getStateUsing(function ($record) {
                        return is_array($record->image) && !empty($record->image)
                            ? $record->image[0] 
                            : null; 
                    })
                    ->width(50) // Atur lebar gambar
                    ->height(50) // Atur tinggi gambar
                    ->searchable(),    

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Ketegori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.generalCategory.slug')
                    ->label('General Ketegori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('het_price')
                    ->label('HET')
                    ->sortable()
                    ->money('idr'), 
                
                Tables\Columns\TextColumn::make('total_stock')
                    ->label('Total Stok')
                    ->getStateUsing(fn (Product $record) => $record->total_stock)
                    ->sortable() // Opsional: memungkinkan kolom diurutkan
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat ')
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
            ReviewsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
