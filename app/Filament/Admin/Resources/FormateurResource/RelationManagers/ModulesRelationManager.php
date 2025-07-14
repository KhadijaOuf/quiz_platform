<?php

namespace App\Filament\Admin\Resources\FormateurResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';
    protected static ?string $title = 'Modules assurés';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')->label('Nom du module')->required()->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')->label('Nom du module'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Créé le'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Créer et assigner un module'),
                Tables\Actions\AttachAction::make()->label('Assigner un module existant')
                ->form([
                        Forms\Components\Select::make('recordId')
                            // recordId est une clé spéciale utilisée par Filament dans une action AttachAction pour identifier l’élément à attacher.
                            ->label('Module')
                            ->options(\App\Models\Module::all()->pluck('nom', 'id'))
                            // récupère tous les modules de la base de données et transforme cette collection en un tableau clé-valeur                            ->searchable(),
                ]),
                
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Modifier'),
                Tables\Actions\DetachAction::make()->label('Retirer'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()->label('Retirer les modules sélectionnés'),
            ]);
    }

    // Autoriser la création de nouveaux modules depuis la relation
    public function canCreate(): bool
    {
        return true;
    }

    // Autoriser l’attachement de modules existants
    public function canAttach(): bool
    {
        return true;
    }
}
