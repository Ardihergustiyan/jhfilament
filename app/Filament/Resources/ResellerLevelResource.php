<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResellerLevelResource\Pages;
use App\Filament\Resources\ResellerLevelResource\RelationManagers;
use App\Models\ResellerLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ResellerLevelResource extends Resource
{
    protected static ?string $model = ResellerLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Reseller Levels';

    protected static ?string $pluralLabel = 'Reseller Levels';

    protected static ?string $slug = 'reseller-levels';

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->maxLength(255)
                    ->disabled()
                    ->required()
                    ->dehydrated()
                    ->unique(ResellerLevel::class, 'slug', ignoreRecord: true),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->label('Created After')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                    ])
                    ->query(fn ($query, $data) => $query->when(
                        $data['created_from'],
                        fn ($query, $date) => $query->whereDate('created_at', '>=', $date)
                    )),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListResellerLevels::route('/'),
            'create' => Pages\CreateResellerLevel::route('/create'),
            'edit' => Pages\EditResellerLevel::route('/{record}/edit'),
        ];
    }
}
