<?php

namespace App\Filament\Admin\Resources\SpecialiteResource\Pages;

use App\Filament\Admin\Resources\SpecialiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\Concerns\HasRelationManagers;

class ViewSpecialite extends ViewRecord
{
    use HasRelationManagers;
    protected static string $resource = SpecialiteResource::class;
}
