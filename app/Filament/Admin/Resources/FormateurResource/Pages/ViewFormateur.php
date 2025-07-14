<?php

namespace App\Filament\Admin\Resources\FormateurResource\Pages;

use App\Filament\Admin\Resources\FormateurResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\Concerns\HasRelationManagers;

class ViewFormateur extends ViewRecord
{
    use HasRelationManagers; //activer les relations
                             // permet d’afficher les RelationManagers dans la page de détail de chaque formateur.
    protected static string $resource = FormateurResource::class;
}
