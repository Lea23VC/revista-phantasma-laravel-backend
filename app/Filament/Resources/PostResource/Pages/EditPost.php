<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Post;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make("Go to post")->label(__("Go to post"))
                ->url(fn (Post $record): string => env("FRONTEND_URL") . 'post/' . $record->slug, true),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterValidate(): void
    {
        $post = $this->record;

        $data = $this->data;
        $featuredImage = $post?->featuredImage();
        if ($featuredImage && isset($data['previewImagePosition'])) {

            $featuredImage->setCustomProperty('preview-position', $data['previewImagePosition']);
        }

        if (isset($data['bannerImagePosition'])) {
            $featuredImage->setCustomProperty('banner-position', $data['bannerImagePosition']);
        }
        $featuredImage?->save();
    }
}
