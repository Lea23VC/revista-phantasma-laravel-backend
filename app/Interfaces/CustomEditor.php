<?php

namespace App\Interfaces;

use Filament\Forms\Components\RichEditor;
use LaraZeus\Sky\Editors\ContentEditor;

class CustomEditor implements ContentEditor
{
    protected string $fileAttachmentsDisk = 's3';
}
