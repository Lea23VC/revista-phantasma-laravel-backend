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
        Log::info($root);
        if (isset($root['data']['url'])) {
            if ($root['type'] == 'categories') {
                $root['data']['url'] = '/phantasma/' . $root['data']['url'];
            } else {
                $root['data']['url'] = '/' . $root['data']['url'];
            }
        }

        return $root['data'];
    }
}
