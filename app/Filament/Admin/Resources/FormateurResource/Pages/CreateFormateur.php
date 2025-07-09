<?php

namespace App\Filament\Admin\Resources\FormateurResource\Pages;

use App\Filament\Admin\Resources\FormateurResource;
use App\Models\User;
use App\Models\Formateur;
use Filament\Resources\Pages\CreateRecord;

class CreateFormateur extends CreateRecord
{
    protected static string $resource = FormateurResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Créer le User
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->assignRole('formateur');

        // Injecter l'id dans les données du formateur
        $data['user_id'] = $user->id;

        // Supprimer les champs qui n'existent pas dans formateurs
        unset($data['name'], $data['email'], $data['password']);

        return $data;
    }
}
