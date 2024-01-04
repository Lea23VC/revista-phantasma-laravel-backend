<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Log;
use RyanChandler\FilamentNavigation\Models\Navigation as FilamentNavigation;

final readonly class Navigation
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $menu = FilamentNavigation::fromHandle('main-menu');

        return $menu->items;
    }
}
