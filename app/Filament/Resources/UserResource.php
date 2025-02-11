<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
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
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('user-images'),
                    Forms\Components\Textarea::make('address')
                        ->maxLength(65535),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn($state)=> filled($state))
                        ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord),

                    Select::make('roles')
                        ->relationship('roles', 'name')
                        ->preload()
                        ->live()
                        ->required()
                        ->afterStateUpdated(fn ($state, Set $set) => $set('reseller_level_id', null)),

                    // Select::make('reseller_level_id')
                    //     ->relationship('resellerLevel', 'name') // Pastikan relasi "resellerLevel" benar
                    //     ->label('Reseller Level')
                    //     ->visible(function (Get $get) {
                    //         $role = $get('roles'); // Ambil nilai 'roles'
                    //         // Debug tipe data dan isi nilai
                    //         info('Role type: ' . gettype($role)); 
                    //         info('Role value: ' . json_encode($role)); // Log nilai sebagai JSON
                            
                    //         return $role === 'Reseller'; // Periksa nilai
                    //     })
                    //     ->required(function (Get $get) {
                    //         $role = $get('roles'); // Debug nilai roles
                    //         return $role === 'Reseller';
                    //     }),
                    
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
            // Tables\Columns\TextColumn::make('email_verified_at')
            //     ->label('Email Verified At')
            //     ->sortable()
            //     ->dateTime()
            //     ->description(fn ($record) => $record->email_verified_at ? 'Verified' : 'Not Verified'),
            Tables\Columns\TextColumn::make('phone_number')
                ->label('Phone Number'),
            Tables\Columns\BooleanColumn::make('is_active')
                ->label('Active'),
            
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
