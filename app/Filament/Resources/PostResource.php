<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
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
use Filament\Tables\Columns\TagsColumn;
use Filament\Resources\Forms\Components;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Get;
use Filament\Forms\Components\Tabs;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;

use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $postInfoTab = Tabs\Tab::make("Post Info")->schema([
            Grid::make([
                'default' => 1,
                'sm' => 2,
            ])
                ->schema([
                    Forms\Components\TextInput::make('title')->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                        if (!$get('is_slug_changed_manually') && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    })->reactive()->required(),
                    Forms\Components\TextInput::make('slug')
                        ->afterStateUpdated(function (Set $set) {
                            $set('is_slug_changed_manually', true);
                        })->unique(ignorable: fn ($record) => $record)
                        ->required(),
                    Forms\Components\Hidden::make('is_slug_changed_manually')
                        ->default(false)
                        ->dehydrated(false),

                    DatePicker::make('publish_at')->native(false)->default(now()),

                    Select::make('author_id')
                        ->relationship(name: 'author', titleAttribute: 'name')
                        ->searchable()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required(),
                            Forms\Components\TextInput::make('url'),
                            SpatieMediaLibraryFileUpload::make('profilePic')
                                ->label('Profile pic')->disk('s3')->responsiveImages()
                                ->visibility('public')->directory('authors_profile_pic')->image(),
                        ])->required()
                        ->preload(),

                    TinyEditor::make('content')->showMenuBar()->language('es')->toolbarSticky(true)->columnSpan('full')->fileAttachmentsDisk('s3')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('posts_content')->maxWidth("740px")->required(),
                    SpatieMediaLibraryFileUpload::make('featuredImage')
                        ->label('Featured image')
                        ->disk('s3')->visibility('public')->directory('post_uploads')
                        ->image()->responsiveImages()
                        ->optimize('webp')->required(),
                    Select::make('categories')->searchable()
                        ->options(function () {
                            return Category::pluck('name', 'id');
                        })->multiple(true)->relationship('categories', 'name')->preload()->required(),

                ]),
        ]);

        $attachmentsTab =  Tabs\Tab::make("Post attachments")->schema([Section::make("Attachments")->schema(

            [

                TinyEditor::make('attachments_paragraph')
                    ->showMenuBar()->language('es')->toolbarSticky(true)
                    ->columnSpan('full')->fileAttachmentsDisk('s3')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('posts_attachment_paragraph')
                    ->maxWidth("740px"),
                Select::make('position')
                    ->options([
                        'start' => 'At the start of the post',
                        'end' => 'At the end of the post',
                    ]),
                Repeater::make('attachments')->label("Attachments files")->relationship('attachments')
                    ->schema([
                        Forms\Components\TextInput::make('title'),
                        Textarea::make('description'),
                        SpatieMediaLibraryFileUpload::make('attachment')
                            ->collection('files')->disk('s3')
                            ->visibility('public')
                            ->directory('post_attachments'),
                    ])->grid(2)->columnSpan('full')->defaultItems(0)
            ]
        )]);

        $SEOtab = Tabs\Tab::make("SEO")->schema([]);

        return $form
            ->schema([
                //
                Tabs::make('Label')->tabs([
                    $postInfoTab, $attachmentsTab, $SEOtab
                ])->contained(false)->columnSpan('full'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id'),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('categories.name')
                    ->listWithLineBreaks()->badge(),
                SpatieMediaLibraryImageColumn::make('featuredImage')->square()->disk('s3')->visibility('public'),
                TextColumn::make('author.name')->label('Author'),
            ])
            ->filters([
                //
                SelectFilter::make('categories')->relationship('categories', 'name')->multiple()->preload(),

            ])
            ->actions([
                Action::make("Go to post")->url(fn (Post $record): string => 'https://revista-phantasma-nuxt-vue-frontend.vercel.app/post/' . $record->slug, true),
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
            'view' => Pages\ViewPost::route('/{record}'),
        ];
    }
}
