<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPosts = Post::count();
        $publishedPosts = Post::where('is_published', true)->count();
        $unpublishedPosts = Post::where('is_published', false)->count();
        $totalCategories = Category::count();

        // Date-related stats
        $postsThisMonth = Post::whereMonth('publish_at', Carbon::now()->month)->count();
        $postsLast7Days = Post::where('publish_at', '>=', Carbon::now()->subDays(7))->count();

        // Calculate the average posts per month
        $monthsActive = Post::whereNotNull('publish_at')->distinct()->selectRaw('YEAR(publish_at) as year, MONTH(publish_at) as month')->count();
        $averagePostsPerMonth = $monthsActive > 0 ? round($totalPosts / $monthsActive, 2) : 0;

        return [
            Stat::make('Total de Publicaciones', $totalPosts)
                ->description('Número total de publicaciones')
                ->color('primary'),

            Stat::make('Publicadas', $publishedPosts)
                ->description('Publicaciones que están publicadas')
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('No Publicadas', $unpublishedPosts)
                ->description('Publicaciones que no están publicadas')
                ->color('danger')
                ->descriptionIcon('heroicon-m-arrow-trending-down'),

            Stat::make('Total de Categorías', $totalCategories)
                ->description('Número total de categorías disponibles')
                ->color('info'),

            Stat::make('Publicaciones Este Mes', $postsThisMonth)
                ->description('Publicaciones durante este mes')
                ->color('gray'),

            Stat::make('Publicaciones en los Últimos 7 Días', $postsLast7Days)
                ->description('Publicaciones en los últimos 7 días')
                ->color('gray'),

            Stat::make('Promedio de Publicaciones por Mes', $averagePostsPerMonth)
                ->description('Promedio de publicaciones mensuales')
                ->color('gray'),
        ];
    }
}
