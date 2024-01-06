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

    public function resolveChildrenField($rootValue, $args)
    {

        $children = $rootValue["children"]; // Assuming $rootValue is a Navigation model instance

        // Return null if children is an empty array
        return count($children) > 0 ? $children : null;
    }
}
