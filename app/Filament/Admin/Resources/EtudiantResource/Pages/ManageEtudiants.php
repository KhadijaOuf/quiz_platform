<?php

namespace App\Filament\Admin\Resources\EtudiantResource\Pages;

use App\Filament\Admin\Resources\EtudiantResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEtudiants extends ManageRecords
{
    protected static string $resource = EtudiantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
