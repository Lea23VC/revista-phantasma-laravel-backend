<?php

namespace App\Editors;

use Filament\Forms\Components\Component;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor as RichEditorAlias;
use Filament\Forms\Components\Textarea;
use LaraZeus\Sky\Classes\ContentEditor;
use FilamentTiptapEditor\Enums\TiptapOutput;

class RichEditor implements ContentEditor
{
    public static function component(): Component
    {
        if (class_exists(RichEditorAlias::class)) {
            return RichEditorAlias::make('content')
                ->fileAttachmentsDisk('s3')
                ->fileAttachmentsVisibility('public')
                ->fileAttachmentsDirectory('uploads')
                ->required();
        }

        return Textarea::make('content')->required();
    }

    public static function render(string $content): string
    {
        return html_entity_decode($content);
    }
}
