<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use DOMDocument;

final readonly class PostEntryImages
{
    /** @param  array{}  $args */
    public function __invoke(mixed $root, array $args, GraphQLContext $context)
    {


        $html = $root->content;

        $imageUrls = $this->extractImageUrls($html);

        return $imageUrls;
    }


    function extractImageUrls($htmlString)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlString); // Suppress warnings from invalid HTML
        $imageUrls = [];

        foreach ($dom->getElementsByTagName('img') as $img) {
            $imageUrls[] = $img->getAttribute('src');
        }

        return $imageUrls;
    }
}
