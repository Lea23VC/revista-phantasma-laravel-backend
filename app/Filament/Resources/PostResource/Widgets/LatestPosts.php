<?php

namespace App\Filament\Resources\PostResource\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class LatestPosts extends BaseWidget
{

    protected static ?string $heading = 'Ultimos Posts';
    protected int | string | array $columnSpan = 'full';


    public function table(Table $table): Table
    {
        return $table
            ->query(
                Post::with(['categories', 'author'])
                    ->latest('publish_at')
                    ->limit(5)

            )
            ->columns([
                TextColumn::make('title')->label(__('Title'))->sortable()->limit(50)->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();

                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }

                    // Only render the tooltip if the column content exceeds the length limit.
                    return $state;
                }),
                TextColumn::make('categories.name')->label(__('Categories'))
                    ->listWithLineBreaks()->badge(),
                TextColumn::make('author.name')->label(__('Author'))->sortable(),
                TextColumn::make('publish_at')->label(__('Published at'))->sortable(),
            ])->paginated(false);
    }
}
