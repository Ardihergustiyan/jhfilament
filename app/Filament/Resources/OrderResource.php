<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms\Components\Hidden;
use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
use Illuminate\Support\Number;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $pluralLabel = 'Order';

    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Order Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pesanan')->schema([
                    Select::make('user_id')
                    ->label('Nama Pengguna')
                    ->relationship('user', 'first_name') 
                    ->required()
                    ->columnSpan(6),
                
                // Select::make('status_id')
                //     ->relationship('status', 'name')
                //     ->label('Order Status'),

                    ToggleButtons::make('status_id')
                                ->default(1)
                                ->label('Status Order')
                                ->inline()
                                ->columnSpan(6)
                                
                                ->required()->options([
                                    '1' => 'Diproses',
                                    '2' => 'Siap Diambil',
                                    '3' => 'Selesai',
                                    '4' => 'Dibatalkan'
                                ])
                                ->colors([
                                    '1' => 'warning',
                                    '2' => 'info',
                                    '3' => 'success',
                                    '4' => 'danger'
                                ])
                                ->icons([
                                    '1' => 'heroicon-m-truck',
                                    '2' => 'heroicon-m-check-badge',
                                    '3' => 'heroicon-m-clipboard-document-check',
                                    '4' => 'heroicon-m-x-circle'
                                ]),
                        

                    Select::make('discount_id')
                        ->relationship('discount', 'name')
                        ->label('Diskon')
                        ->nullable()
                        ->columnSpan(6),
                        
                    Select::make('voucher_id')
                        ->relationship('voucher', 'code') 
                        ->label('Kupon / Voucher')
                        ->searchable()
                        ->nullable()
                        ->preload()
                        ->columnSpan(6),
                    
                    
                Forms\Components\Fieldset::make('Payment Details')
                    ->label('Detail Pembayaran')
                    ->relationship('payment')
                    ->schema([
                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'e-wallet' => 'E Wallet',
                                'cod' => 'Cash On Delivery',
                                'transfer' => 'Bank Transfer'
                            ])
                            ->columnSpan(4)
                            ->required(),
                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending',
                                'dibayar' => 'Dibayar',
                                'gagal' => 'Gagal'
                            ])
                            ->columnSpan(4)
                            ->default('pending')
                            ->required(),
                        TextInput::make('transaction_id')
                            ->label('ID Transaksi')
                            ->columnSpan(4)
                            ->nullable(),
                        ])->columns(12),
                ])->columns(12),
                
                Section::make('Daftar Pesanan')->schema([
                    Repeater::make('Items')
                        ->relationship()
                        ->label('Pesanan')
                        ->schema([
                            Select::make('product_id')
                                ->relationship('product', 'name')
                                ->label('Produk')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->distinct()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->columnSpan(3)
                                ->reactive()
                                // ->afterStateUpdated(fn($state, Set $set) => $set('unit_price', Product::find($state)?->base_price ?? 0))
                                // ->afterStateUpdated(fn($state, Set $set) => $set('total_price', Product::find($state)?->base_price ?? 0)),
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $product = Product::find($state);
                                    $unitPrice = $product?->base_price ?? 0;
                            
                                    $set('unit_price', $unitPrice);
                                    $quantity = $get('quantity') ?? 1; // Ambil jumlah default 1 jika belum diatur
                                    $set('total_price', $unitPrice * $quantity); // Hitung total_price
                                }),
                            
                            Select::make('product_variant_ids') 
                                ->relationship('productVariants', 'color') // Relasi dengan warna produk
                                ->options(function (callable $get) {
                                    $productId = $get('product_id'); // Ambil product_id dari state form
                                    if (!$productId) {
                                        return []; // Kosongkan opsi jika product_id tidak tersedia
                                    }
                                    return ProductVariant::where('product_id', $productId)
                                        ->pluck('color', 'id') // Ambil opsi berdasarkan product_id
                                        ->toArray();
                                })
                                ->multiple() // Mengizinkan banyak pilihan
                                ->label('Pilih Warna')
                                ->searchable()
                                ->preload()
                                ->columnSpan(3)
                                ->placeholder('Pilih warna produk')
                                ->reactive(),
                            
                        
                            TextInput::make('quantity')
                                ->label('Jumlah')
                                ->numeric()
                                ->required()
                                ->default(1)
                                ->minValue(1)
                                ->columnSpan(2)
                                ->reactive()
                                // ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('total_price', $state*$get('unit_price'))),
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $unitPrice = $get('unit_price') ?? 0;
                                    $set('total_price', $unitPrice * $state); // Hitung ulang total_price
                                }),

                            placeholder::make('unit_price')
                                ->label('Harga Satuan')
                                ->content(fn (Get $get) => 
                                    Number::currency($get('unit_price') ?? 0, 'IDR') // Format sebagai mata uang IDR
                                )
                                ->columnSpan(2),
                            Hidden::make('unit_price')
                                ->default(0),

                            placeholder::make('total_price')
                                ->label('Harga Total')
                                ->content(fn (Get $get) => 
                                    Number::currency($get('total_price') ?? 0, 'IDR') // Format sebagai mata uang IDR
                                )
                                ->columnSpan(2),
                            Hidden::make('total_price')
                                ->default(0),
                        ])
                        ->columns(12)
                        ->addActionLabel('Tambah Pesanan'),
                        
                        Placeholder::make('grand_total_placeholder')
                            ->label('Total Harga Keseluruhan')
                        
                            ->content(function (Get $get, Set $set) {
                                $total = 0;
                                if (!$repeaters = $get('Items')) {
                                    return Number::currency($total, 'IDR');
                                }
                    
                                foreach ($repeaters as $key => $repeater) {
                                    $total += $repeater['total_price'] ?? 0; // Use `total_price` directly from the repeater state
                                }
                                $set('total_price', $total);
                                return Number::currency($total, 'IDR');
                        }),
                
                    Hidden::make('total_price')
                        ->default(0)
                        ->dehydrated()         
                ])
        ]);
    }

    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('user.first_name')
                    ->label('nama')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Pembelian')
                    ->money('IDR'),
                TextColumn::make('payment.payment_method')
                    ->label('metode pembayaran')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('payment.payment_status')
                    ->label('status pembayaran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status_id')
                ->label('status pesanan')
                ->options([
                    '1' => 'Diproses',
                    '2' => 'Siap Diambil',
                    '3' => 'Selesai',
                    '4' => 'Dibatalkan'
                ])
                ->searchable()
                ->sortable(),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
        // return [
        //     AddressRelationManager::class
        // ];
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        // Hitung jumlah data dengan status_id 1 atau 2
    $count = static::getModel('status')::whereIn('status_id', [1, 2])->count();

        // Kondisi untuk menentukan warna
        if ($count > 0) {
            return 'warning'; // Kuning
        }

        return 'success'; // Hijau
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
