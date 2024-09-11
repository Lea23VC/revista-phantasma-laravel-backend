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
        $totalPosts = Post::count(); // Total posts for all time
        $publishedPosts = Post::where('is_published', true)->count(); // Published posts for all time
        $unpublishedPosts = Post::where('is_published', false)->count(); // Unpublished posts for all time
        $totalCategories = Category::count(); // Total categories for all time

        // Date-related stats (for all time)
        $postsThisMonth = Post::whereMonth('publish_at', Carbon::now()->month)->count();
        $postsLast7Days = Post::where('publish_at', '>=', Carbon::now()->subDays(7))->count();

        // Calculate average posts per month for the current year
        $currentYear = Carbon::now()->year;
        $postsThisYear = Post::whereYear('publish_at', $currentYear)->count(); // Only count posts from the current year

        // Get the first post of the current year
        $firstPostThisYear = Post::whereYear('publish_at', $currentYear)->orderBy('publish_at', 'asc')->first();

        if ($firstPostThisYear) {
            $firstPostDate = Carbon::parse($firstPostThisYear->publish_at);
            $monthsActiveThisYear = Carbon::now()->diffInMonths($firstPostDate) + 1; // +1 to include the current month
        } else {
            $monthsActiveThisYear = Carbon::now()->month; // Default to current month if no posts this year
        }

        // Calculate the average posts per month for the current year
        $averagePostsPerMonth = $monthsActiveThisYear > 0 ? round($postsThisYear / $monthsActiveThisYear, 2) : 0;

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

            Stat::make("Promedio de Publicaciones por Mes ($currentYear)", $averagePostsPerMonth)
                ->description("Promedio de publicaciones mensuales en $currentYear")
                ->color('gray'),
        ];
    }
}
