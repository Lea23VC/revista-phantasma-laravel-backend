<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Log;

final readonly class ImagePosition
{
    /** @param  array{}  $args */
    public function __invoke(mixed $root, array $args, GraphQLContext $context)
    {
        if (isset($root['custom_properties'])) {
            return [
                'preview' => $root['custom_properties']['preview-position'] ?? 'center',
                'banner' => $root['custom_properties']['banner-position'] ?? 'center'
            ];
        }

        return [
            'preview' => 'center',
            'banner' => 'center'
        ];
    }
}
