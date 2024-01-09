<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EditorialMemberResource\Pages;
use App\Filament\Resources\EditorialMemberResource\RelationManagers;
use App\Models\EditorialMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class EditorialMemberResource extends Resource
{
    protected static ?string $model = EditorialMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): ?string
    {
        return __('Editorial Members');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('Magazine');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')->label("Name")
                    ->autofocus()
                    ->required(),

                TextInput::make('position')->label("Position")
                    ->required(),

                TextInput::make('email')->label("Email")
                    ->required(),

                SpatieMediaLibraryFileUpload::make('profilePic')
                    ->label('Profile pic')->disk('s3')
                    ->visibility('public')
                    ->directory('editorial_members_profile_pic')
                    ->collection('profile_pic')->responsiveImages()
                    ->image()->optimize('webp'),

                Select::make('author_id')->label("Existing author?")
                    ->relationship(name: 'author', titleAttribute: 'name')
                    ->searchable()
                    ->preload(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                TextColumn::make('name')->label(__('Name'))->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('position')->label(__('Position'))->searchable(),
                SpatieMediaLibraryImageColumn::make('profilePic')->label(__('Profile pic'))->collection('profile_pic')->square()->disk('s3')->visibility('public'),

            ])->defaultSort('order', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('up')->icon('heroicon-o-chevron-up')
                    ->action(fn (EditorialMember $record) => $record->moveOrderUp()),
                Tables\Actions\Action::make('down')->icon('heroicon-o-chevron-down')
                    ->action(fn (EditorialMember $record) => $record->moveOrderDown()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEditorialMembers::route('/'),
        ];
    }
}
