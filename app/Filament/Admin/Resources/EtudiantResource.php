<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EtudiantResource\Pages;
use App\Filament\Admin\Resources\EtudiantResource\RelationManagers;
use App\Models\Etudiant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\CreateAction;
use App\Models\User;

class EtudiantResource extends Resource
{
    protected static ?string $model = Etudiant::class;
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom_complet')->label('Nom complet')->maxLength(30),
                Forms\Components\TextInput::make('cin')->required()->maxLength(20),
                Forms\Components\DatePicker::make('date_naissance')->label('Date de naissance')->required()
                    ->native(false), // pour avoir un date picker
                Forms\Components\TextInput::make('adresse')->maxLength(50),
                Forms\Components\Select::make('specialite_id')
                    ->label('Spécialité')
                    ->relationship('specialite', 'nom')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('annee_inscription')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom_complet')->label('Nom complet')->searchable(),
                Tables\Columns\TextColumn::make('cin')->searchable(),
                Tables\Columns\TextColumn::make('date_naissance'),
                Tables\Columns\TextColumn::make('adresse')->searchable(),
                Tables\Columns\TextColumn::make('specialite.nom')->label('Spécialité')->searchable(),
                Tables\Columns\TextColumn::make('annee_inscription')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading('Créer un etudiant')
                    ->form([
                        Forms\Components\TextInput::make('name')->label('Nom utilisateur')->required(),
                        Forms\Components\TextInput::make('email')->label('Email')->required()->email(),
                        Forms\Components\TextInput::make('password')->label('Mot de passe')->required()->password()->minLength(8),
                        Forms\Components\TextInput::make('nom_complet')->label('Nom complet')->required(),
                        Forms\Components\TextInput::make('cin')->required(),
                        Forms\Components\DatePicker::make('date_naissance')->label('Date de naissance')->required()->native(false), 
                        Forms\Components\TextInput::make('adresse')->maxLength(50),
                        Forms\Components\Select::make('specialite_id')
                            ->label('Spécialité')
                            ->options(\App\Models\Specialite::all()->pluck('nom', 'id'))
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('annee_inscription'),
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
                        $user->assignRole('etudiant');
                        // Injecter user_id dans les données Formateur
                        $data['user_id'] = $user->id;
                        // supprimer les champs inutile pour la table 'formateur'
                        unset($data['name'], $data['email'], $data['password']);

                        return $data;
                        // Filament utilise ensuite $data pour Etudiant::create($data)
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
                    ->label('')
                    ->color('info')
                    ->tooltip('Voir les détails')
            ])
            ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEtudiants::route('/'),
        ];
    }
}
