<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Forms\Components;
use Filament\Forms\Components\FileUpload;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TinyEditor::make('content')->required(),
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\Section::make()->columns(2)->relationship('featuredImage')->schema([
                    FileUpload::make('key')->disk('s3')->visibility('public'),

                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('title'),
                ImageColumn::make('featuredImage')->square()->disk('s3')->visibility('public'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
