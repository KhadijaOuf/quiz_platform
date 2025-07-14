<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    

    public static function form(Form $form): Form
    {
        return $form
            // formulaire d'edition
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nom utilisateur')->maxLength(191),
                Forms\Components\TextInput::make('email')->email()->maxLength(191),
                Forms\Components\TextInput::make('password')->label('mot de passe')->maxLength(191),
                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'formateur' => 'Formateur',
                        'etudiant' => 'Etudiant',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nom de l\'utilisateur')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color(function ($state) {
                        return match (strtolower($state)) {
                            'admin' => 'danger',
                            'formateur' => 'info',
                            'etudiant' => 'success',
                            default => 'gray',
                        };
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->label('') 
                    ->tooltip('Modifier'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('') 
                    ->tooltip('Supprimer'),
                Tables\Actions\Action::make('resetPassword')
                    ->icon('heroicon-o-key')
                    ->label('')
                    ->tooltip('Réinitialiser le mot de passe')
                    ->action(function (User $record) {
                        Password::sendResetLink(['email' => $record->email]);
                    })
                    ->requiresConfirmation()
                    ->color('info'),
            ])
            ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make()
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }

    public static function canCreate(): bool {  return false;  /* desactiver la creation de user */ }
    /* Garder l’édition pour :
        Changer mot de passe
        Modifier l’email ou nom
        Changer les rôles (via Spatie)
    */
}
