<?php

namespace App\Navigation;

use RyanChandler\FilamentNavigation\Facades\FilamentNavigation as NavigationAlias;

class CustomFilamentNavigation extends NavigationAlias
{
    public static function component(): Component
    {
        if (class_exists(NavigationAlias::class)) {
            return FilamentNavigation::addItemType('Post link', [
                Select::make('post_id')
                    ->searchable()
                    ->options(function () {
                        return Post::pluck('title', 'id');
                    })
            ]);;
        }
    }

    public static function render(string $content): string
    {
        return html_entity_decode($content);
    }
}
