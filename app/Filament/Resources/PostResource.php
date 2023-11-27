<?php

namespace App\Filament\Resources;

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

use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Get;
use Filament\Forms\Components\Tabs;

use Filament\Forms\Components\Grid;

use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\Action;

use RalphJSmit\Filament\SEO\SEO;

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
                    Forms\Components\TextInput::make('title')->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                            if (!$get('is_slug_changed_manually') && filled($state)) {
                                $set('slug', Str::slug($state));
                            }
                        })->required(),
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
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required(),
                            Forms\Components\TextInput::make('url')
                        ])->searchable()
                        ->preload()->required(),

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

        $attachmentsTab =  Tabs\Tab::make("Post attachments")->schema(
            [


                Repeater::make('attachments')->label("Attachments files")->relationship('attachments')
                    ->schema([
                        TinyEditor::make('description')->label('Attachments description')
                            ->showMenuBar()->language('es')->toolbarSticky(true)
                            ->columnSpan('full')->fileAttachmentsDisk('s3')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsDirectory('description_attachments')
                            ->maxWidth("740px"),
                        Select::make('position')
                            ->options([
                                'start' => 'At the start of the post',
                                'end' => 'At the end of the post',
                            ]),
                        SpatieMediaLibraryFileUpload::make('attachment')
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

                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('categories.name')
                    ->listWithLineBreaks()->badge(),
                SpatieMediaLibraryImageColumn::make('featuredImage')->square()->disk('s3')->visibility('public'),
                TextColumn::make('author.name')->label('Author'),
                TextColumn::make('publish_at')->dateTime('d/m/Y')->sortable(),
            ])->defaultSort('publish_at', 'desc')
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
