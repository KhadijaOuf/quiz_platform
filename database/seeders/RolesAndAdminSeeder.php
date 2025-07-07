<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crée les rôles s'ils n'existent pas encore
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'formateur']);
        Role::firstOrCreate(['name' => 'etudiant']);

        // Crée un utilisateur admin si l'email n'existe pas déjà
        $admin = User::firstOrCreate([
            'email' => 'khadija.oufquir22@gmail.com',
        ], [
            'name' => 'epgAdmin',
            'password' => Hash::make('epgadmin2025'), // Mot de passe sécurisé
        ]);

        // Donne le rôle "admin" à ce compte
        $admin->assignRole('admin');
    }
}
