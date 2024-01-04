<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class Slug
{
    /** @param  array{}  $args */
    public function __invoke(mixed $root, array $args, GraphQLContext $context)
    {
        if ($root['type'] == 'categories') {
            $root['data']['slug'] = '/phantasma/' . $root['data']['slug'];
        } else {
            $root['data']['slug'] = '/' . $root['data']['slug'];
        }

        return $root['data'];
    }
}
