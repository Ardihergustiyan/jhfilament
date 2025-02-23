<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\ResellerLevel;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section 1: Informasi Dasar Pengguna
                Section::make('Informasi Dasar Pengguna')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->default(now()),
                        Forms\Components\TextInput::make('phone_number')
                            ->tel()
                            ->required()
                            ->maxLength(15),
                        Forms\Components\Textarea::make('address')
                            ->maxLength(65535),
                    ])
                    ->columns(2), // Menampilkan field dalam 2 kolom

                // Section 2: Pengaturan Akun
                Section::make('Pengaturan Akun')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord),
                    ])
                    ->columns(2), // Menampilkan field dalam 2 kolom

                // Section 3: Role dan Reseller Level
                Section::make('Role dan Reseller Level')
                    ->schema([
                        Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->reactive()
                            ->required()
                            ->default(function ($record) {
                                // Set nilai default berdasarkan data yang sudah ada
                                return $record ? $record->roles->first()?->id : null;
                            })
                            ->afterStateUpdated(function ($state, Set $set) {
                                // Jika role yang dipilih bukan reseller (ID 3), set reseller_level_id menjadi null
                                if ($state != 3) {
                                    $set('reseller_level_id', null);
                                }
                            }),
                        Select::make('reseller_level_id')
                            ->label('Reseller Level')
                            ->options(function (callable $get) {
                                $role = $get('roles'); // Ambil nilai roles yang dipilih (berupa ID)

                                // Jika roles adalah 'Reseller' (ID = 3), tampilkan daftar reseller level
                                if ($role == 3) {
                                    return ResellerLevel::all()->pluck('name', 'id'); // Ambil nama dan ID reseller level
                                }

                                // Jika bukan 'Reseller', kembalikan array kosong
                                return [];
                            })
                            ->hidden(fn (callable $get) => $get('roles') != 3) // Sembunyikan jika roles bukan 'Reseller' (ID = 3)
                            ->required(fn (callable $get) => $get('roles') == 3) // Wajib diisi jika roles adalah 'Reseller' (ID = 3)
                            ->multiple(false), // Pastikan ini false jika hanya satu nilai yang diizinkan
                    ])
                    ->columns(2), // Menampilkan field dalam 2 kolom
            ]);
    }

    public static function table(Table $table): Table
    {
        
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('first_name')
                ->label('Name')
                ->searchable()
                ->sortable()
                ->getStateUsing(function ($record) {
                    return $record->first_name . ' ' . $record->last_name;
                }),
            Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('roles.name')
                ->label('Role')
                ->searchable(),

            Tables\Columns\TextColumn::make('phone_number')
                ->label('Phone Number'),
            Tables\Columns\BooleanColumn::make('is_active')
                ->label('Active'),
            Tables\Columns\TextColumn::make('resellerLevel.name') // Akses melalui relasi reseller
                ->label('Reseller Level')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime(),
        ])
        ->filters([
            TernaryFilter::make('is_active')
                ->label('Active Status')
                ->trueLabel('Active')
                ->falseLabel('Inactive'),
            Filter::make('email_verified')
                ->label('Email Verified')
                ->query(fn (Builder $query) => $query->whereNotNull('email_verified_at'))
                ->toggle(),
                
            // Tambahkan filter berdasarkan role menggunakan Spatie
            SelectFilter::make('roles') // Filter berdasarkan roles
                ->label('Role')
                ->options([
                    'Admin' => 'Admin',
                    'Reseller' => 'Reseller',
                    'Customer' => 'Customer',
                ])
                ->relationship('roles', 'name') // Gunakan relasi roles dan kolom name
                ->placeholder('Pilih Role'), // Opsional: Tambahkan placeholder
        ])
        ->actions([
            ActionGroup::make([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                ])
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'email'];
    }
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
