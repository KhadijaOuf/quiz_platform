<?php

namespace App\Filament\Admin\Resources\SpecialiteResource\Pages;

use App\Filament\Admin\Resources\SpecialiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Pages\Concerns\HasRelationManagers;

class ManageSpecialites extends ManageRecords
{
    protected static string $resource = SpecialiteResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
