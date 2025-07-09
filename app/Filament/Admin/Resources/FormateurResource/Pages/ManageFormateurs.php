<?php

namespace App\Filament\Admin\Resources\FormateurResource\Pages;

use App\Filament\Admin\Resources\FormateurResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Models\User;
use App\Models\Formateur;

class ManageFormateurs extends ManageRecords
{
    protected static string $resource = FormateurResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
