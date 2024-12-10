<?php

namespace App\Actions;


use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;

class GetPostFeatureImageUrlAction
{
    use AsAction;

    public function handle($temporaryFile, ?Post $record)
    {
        $temporaryFileKey = array_key_first($temporaryFile);
        if ($temporaryFileKey) {
            $temporaryFile = $temporaryFile[$temporaryFileKey];
            if (is_array($temporaryFile) || !$record) {

                // check if $temporaryFile is a string
                if (is_string($temporaryFile)) {

                    return null;
                }

                $url = Storage::disk('s3')->temporaryUrl($temporaryFile->getRealPath(), now()->addMinutes(5));
            } else {
                $url = $record?->featuredImage()?->getUrl('preview');
            }
            return $url;
        } else {
            return null;
        }
    }
}
