<?php

use LaraZeus\Sky\Editors;

class CustomEditor extends Editors
{
    public function __construct()
    {
        parent::__construct();

        $this->setEditor(Editors\TipTapEditor::class);
    }
}
