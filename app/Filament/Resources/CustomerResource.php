<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CustomerResource extends Resource
{
    protected static ?string $label = 'Customer';
    protected static ?string $pluralLabel = 'Customers';

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function canViewAny(): bool
    {
        // Hanya admin yang bisa mengakses resource ini
        return Auth::user()->hasRole('superAdmin');
    }

    public static function canCreate(): bool
    {
        // Hanya superAdmin yang bisa membuat permission baru
        return Auth::user()->hasRole('superAdmin');
    }

    public static function canEdit($record): bool
    {
        // Hanya superAdmin yang bisa mengedit permission
        return Auth::user()->hasRole('superAdmin');
    }

    public static function canDelete($record): bool
    {
        // Hanya superAdmin yang bisa menghapus permission
        return Auth::user()->hasRole('superAdmin');
    }


    public static function shouldRegisterNavigation(): bool
    {
        // Pastikan user yang login memiliki role 'Admin'
        return auth()->check() && auth()->user()->hasRole('Admin');
    }
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Hanya ambil user dengan role 'reseller'
        return parent::getEloquentQuery()->role('Customer');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->label('First Name'),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->label('Last Name'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone_number')
                    ->label('Phone Number'),
                Forms\Components\FileUpload::make('image')
                    ->label('Profile Picture')
                    ->image(),
                Forms\Components\Textarea::make('address')
                    ->label('Address')
                    ->placeholder('Enter your address here') // Placeholder untuk membantu pengguna
                    ->rows(3) // Jumlah baris untuk teks area
                    ->maxLength(500) // Batasan panjang maksimal teks
                    // Agar field ini mengambil seluruh kolom pada form
                    ->helperText('Please enter your full address, maximum 500 characters.'), // Pesan bantu di bawah field,
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\BooleanColumn::make('is_active')->label('Active'),
                Tables\Columns\TextColumn::make('phone_number')->label('Phone'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
