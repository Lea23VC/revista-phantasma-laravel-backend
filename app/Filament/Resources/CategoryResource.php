<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Get;

use Filament\Forms\Set;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): ?string
    {
        return __('Categories');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('Magazine');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('Name'))
                    ->required()
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                        if (!$get('is_slug_changed_manually') && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    })->reactive()
                    ->maxLength(255),


                Forms\Components\TextInput::make('slug')
                    ->afterStateUpdated(function (Set $set) {
                        $set('is_slug_changed_manually', true);
                    })
                    ->required(),

                Forms\Components\Hidden::make('is_slug_changed_manually')
                    ->default(false)
                    ->dehydrated(false),

                SpatieMediaLibraryFileUpload::make('background')
                    ->downloadable()
                    ->label(__('Background image'))
                    ->disk('s3')->visibility('public')->directory('category_backgrounds')
                    ->collection('backgrounds')->image()
                    ->responsiveImages()
                    ->optimize('webp')->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('order', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('up')->icon('heroicon-o-chevron-up')
                    ->action(fn (Category $record) => $record->moveOrderUp()),
                Tables\Actions\Action::make('down')->icon('heroicon-o-chevron-down')
                    ->action(fn (Category $record) => $record->moveOrderDown()),
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
            RelationManagers\PostsRelationManager::class,

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

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->orderBy('order');
    }
}
