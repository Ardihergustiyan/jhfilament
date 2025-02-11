<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\GeneralCategory;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    
    protected static ?string $pluralLabel = 'Kategori';

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Product Management';


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Split::make([
                Group::make()
                    ->schema([
                        Section::make('General Information')
                        ->label('Informasi Umum')
                        ->schema([
                            Forms\Components\Select::make('general_category_id')
                                ->label('Kategori Umum')
                                ->relationship('generalCategory', 'name') 
                                ->required()
                                ->reactive() 
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $name = $get('name');
                                    if ($name) {
                                        // Generate slug berdasarkan name
                                        $generalCategory = GeneralCategory::find($state);
                                        if ($generalCategory) {
                                            $set('slug', Str::slug($generalCategory->name . '-' . $name));
                                        }
                                    } else {
                                        // Kosongkan slug jika name belum diisi
                                        $set('slug', null);
                                    }
                                }),
    
                            TextInput::make('name')
                                ->label('Nama Kategori')
                                ->required()
                                ->live(onBlur: true)
                                ->maxLength(255)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $generalCategory = GeneralCategory::find($get('general_category_id'));
                                    if ($generalCategory) {
                                        $set('slug', Str::slug($generalCategory->name . '-' . $state));
                                    }
                                }),
                        ]),
                    ])
                    ->columnSpan(3), 

                Group::make()
                    ->schema([
                        Section::make('Status')->schema([
                            Forms\Components\TextInput::make('slug')
                                ->maxLength(255)
                                ->disabled()
                                ->required()
                                ->dehydrated()
                                ->unique(Category::class, 'slug', ignoreRecord: true),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true),
                        ]),
                    ])
                    ->columnSpan(1), // Lebar bagian kanan (1 bagian)
            ])
            ->columnSpanFull() // Memastikan layout ini memenuhi seluruh lebar form
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('generalCategory.name')
                    ->label('Kategori Umum')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
