<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPosts = Post::count();
        $publishedPosts = Post::where('is_published', true)->count();
        $unpublishedPosts = Post::where('is_published', false)->count();
        $totalCategories = Post::with('categories')->get()->pluck('categories')->flatten()->count();
        $totalAttachments = Post::with('attachments')->get()->pluck('attachments')->flatten()->count();

        return [
            Stat::make(_('Total Posts'), $totalPosts)
                ->description(_('Total number of posts'))
                ->color('primary'),

            Stat::make(_('Published Posts'), $publishedPosts)
                ->description(_('Currently published posts'))
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make(_('Unpublished Posts'), $unpublishedPosts)
                ->description(_('Currently unpublished posts'))
                ->color('danger')
                ->descriptionIcon('heroicon-m-arrow-trending-down'),

            Stat::make(_('Total Categories'), $totalCategories)
                ->description(_('Categories assigned to posts'))
                ->color('info'),

            Stat::make(_('Total Attachments'), $totalAttachments)
                ->description(_('Total number of attachments in posts'))
                ->color('warning'),
        ];
    }
}
