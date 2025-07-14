<?php

namespace App\Filament\Admin\Resources\FormateurResource\Pages;

use App\Filament\Admin\Resources\FormateurResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Concerns\HasRelationManagers;

class EditFormateur extends EditRecord
{
    use HasRelationManagers;
    protected static string $resource = FormateurResource::class;
    
}
