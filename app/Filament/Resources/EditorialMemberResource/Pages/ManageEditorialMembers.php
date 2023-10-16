<?php

namespace App\Filament\Resources\EditorialMemberResource\Pages;

use App\Filament\Resources\EditorialMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEditorialMembers extends ManageRecords
{
    protected static string $resource = EditorialMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
