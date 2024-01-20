<?php

namespace App\Filament\Resources;

use Illuminate\Support\Carbon;
use Filament\Tables\Columns\IconColumn;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Enums\ActionsPosition;

use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Get;
use Filament\Forms\Components\Tabs;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\ToggleButtons;

use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Toggle;


use RalphJSmit\Filament\SEO\SEO;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';


    public static function getLabel(): ?string
    {
        return __('Posts');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('Magazine');
    }

    public static function form(Form $form): Form
    {
        $postInfoTab = Tabs\Tab::make("Post Info")->schema([
            Grid::make([
                'default' => 1,
                'sm' => 2,
            ])
                ->schema([
                    Forms\Components\TextInput::make('title')->label(__('Title'))->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                            if (!$get('is_slug_changed_manually') && filled($state)) {
                                $set('slug', Str::slug($state));
                            }
                        })->required()->columnSpan('full'),

                    Forms\Components\TextInput::make('slug')
                        ->afterStateUpdated(function (Set $set) {
                            $set('is_slug_changed_manually', true);
                        })->unique(ignorable: fn ($record) => $record)
                        ->required(),
                    Select::make('author_id')->label(__('Author'))
                        ->relationship(name: 'author', titleAttribute: 'name')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required(),
                            Forms\Components\TextInput::make('url')
                        ])->searchable()
                        ->preload()->required(),
                    Forms\Components\Hidden::make('is_slug_changed_manually')
                        ->default(false)
                        ->dehydrated(false),

                    DatePicker::make('publish_at')->label(__('Publish at'))
                        ->format('Y-m-d')
                        ->native(false)->default(now()),

                    ToggleButtons::make('is_published')->label(__('Published?'))
                        ->boolean()
                        ->inline()
                        ->default(true),

                    TinyEditor::make('content')->label(__('Content'))
                        ->showMenuBar()->language('es')->toolbarSticky(true)->columnSpan('full')->fileAttachmentsDisk('s3')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('posts_content')->maxWidth("740px")->required(),
                    SpatieMediaLibraryFileUpload::make('featuredImage')
                        ->label(__('Featured image'))
                        ->disk('s3')->visibility('public')->directory('post_uploads')
                        ->image()->responsiveImages()
                        ->conversion('featured')
                        // ->maxSize(1024)
                        ->optimize('webp')->required(),
                    Select::make('categories')->label(__('Categories'))->searchable()
                        ->options(function () {
                            return Category::pluck('name', 'id');
                        })->multiple(true)->relationship('categories', 'name')->preload()->required(),

                ]),
        ]);

        $attachmentsTab =  Tabs\Tab::make("Post attachments")->label(__('Post attachments'))->schema(
            [


                Repeater::make('attachments')->label(__("Attachments files"))->relationship('attachments')
                    ->schema([
                        TinyEditor::make('description')->label(__('Attachments description'))
                            ->showMenuBar()->language('es')->toolbarSticky(true)
                            ->columnSpan('full')->fileAttachmentsDisk('s3')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsDirectory('description_attachments')
                            ->maxWidth("740px"),
                        Select::make('position')->label(__('Position'))
                            ->options([
                                'start' => __('At the start of the post'),
                                'end' => __('At the end of the post'),
                            ]),
                        SpatieMediaLibraryFileUpload::make('attachment')->label(__('Attachment file'))
                            ->collection('files')->disk('s3')
                            ->visibility('public')->reorderable(true)->preserveFilenames(true)
                            ->directory('post_attachments')->multiple(true),
                    ])->columnSpan('full')->defaultItems(0)
            ]
        );

        $SEOtab = Tabs\Tab::make("SEO")->schema([
            SEO::make(),
        ]);

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

                TextColumn::make('title')->label(__('Title'))
                    ->tooltip(fn (Post $record): string => strlen($record->title) > 60 ? $record->title : '')
                    ->formatStateUsing(function (string $state) {
                        return strlen($state) > 60 ? substr($state, 0, 60) . '...' : $state;
                    })->searchable()->sortable(),
                TextColumn::make('categories.name')->label(__('Categories'))
                    ->listWithLineBreaks()->badge(),
                IconColumn::make('is_published')->label(__('Published?'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->falseIcon('heroicon-o-pencil'),
                SpatieMediaLibraryImageColumn::make('featuredImage')->label(__('Featured image'))->square()->disk('s3')->visibility('public')->conversion('preview'),
                TextColumn::make('author.name')->label(__('Author')),
                TextColumn::make('publish_at')->label(__('Publish at'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])->defaultSort('publish_at', 'desc')
            ->filters([
                //
                SelectFilter::make('categories')->label(__('Categories'))->relationship('categories', 'name')->multiple()->preload(),

            ])
            ->actions([
                Action::make("Go to post")->label(__('Go to post'))
                    ->hidden(fn (Post $record): bool => !$record->is_published)
                    ->url(fn (Post $record): string => env("FRONTEND_URL") . 'post/' . $record->slug, true),
                Tables\Actions\EditAction::make(),
            ], position: ActionsPosition::BeforeColumns)
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
