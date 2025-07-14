<?php

namespace App\Filament\Admin\Resources\SpecialiteResource\Pages;

use App\Filament\Admin\Resources\SpecialiteResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Concerns\HasRelationManagers;

class EditSpecialite extends EditRecord
{
    use HasRelationManagers;

    protected static string $resource = SpecialiteResource::class;
}
