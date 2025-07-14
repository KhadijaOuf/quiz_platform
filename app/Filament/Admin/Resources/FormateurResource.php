<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FormateurResource\Pages;
use App\Filament\Admin\Resources\FormateurResource\RelationManagers\ModulesRelationManager;
use App\Models\Formateur;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormateurResource extends Resource
{
    protected static ?string $model = Formateur::class;
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('user_id')
                //     ->relationship('user', 'name')
                //     ->required(),
                // formulaire par defaut (pour creation et edit)
                // on a personnaliser le formulaire de creation dans headerActions, donc ce form reste pour le edit
                Forms\Components\TextInput::make('nom_complet')->label('Nom complet')->maxLength(30),
                Forms\Components\TextInput::make('cin')->required()->maxLength(10),
                Forms\Components\TextInput::make('adresse')->maxLength(50),
                Forms\Components\TextInput::make('departement')->maxLength(50),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Nom utilisateur')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nom_complet')->label('Nom complet')->searchable(),
                Tables\Columns\TextColumn::make('cin')->searchable(),
                Tables\Columns\TextColumn::make('user.email')->label('Email')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('adresse')->searchable(),
                Tables\Columns\TextColumn::make('departement')->searchable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading('Créer un formateur')
                    //->disableCreateAnotherButton()  // Désactive le bouton "Créer & ajouter une autre"
                    ->form([
                        Forms\Components\TextInput::make('name')->label('Nom utilisateur')->required(),
                        Forms\Components\TextInput::make('email')->label('Email')->required()->email(),
                        Forms\Components\TextInput::make('password')
                            ->label('Mot de passe')
                            ->required()
                            ->password()
                            ->minLength(8),
                        Forms\Components\TextInput::make('nom_complet')->label('Nom complet')->required(),
                        Forms\Components\TextInput::make('cin')->required(),
                        Forms\Components\TextInput::make('adresse'),
                        Forms\Components\TextInput::make('departement'),
                    ])
                    ->mutateFormDataUsing(function (array $data): array 
                    // mutateFormDataUsing() intervient juste avant l'envoi à la base, injecte user_id, élimine les champs inutiles.
                    {
                        // Création du User
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => bcrypt($data['password']),
                        ]);
                        // assigner le role de formateur
                        $user->assignRole('formateur');
                        // Injecter user_id dans les données Formateur
                        $data['user_id'] = $user->id;
                        // supprimer les champs inutile pour la table 'formateur'
                        unset($data['name'], $data['email'], $data['password']);

                        return $data;
                        // Filament utilise ensuite $data pour Formateur::create($data)
                    })
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
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->label('Voir Modules')
                    ->color('info')
                    ->tooltip('Voir les détails')
            ])
            ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make()
            ]);
    }

    public static function getRelations(): array
    {
    return [
        ModulesRelationManager::class,
    ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFormateurs::route('/'),
            'view' => Pages\ViewFormateur::route('/{record}'), // afficher une page de détail avec les modules assigné au formateur
            'edit' => Pages\EditFormateur::route('/{record}/edit'),
        ];
    }



}
