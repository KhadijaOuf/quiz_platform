<?php

namespace App\Filament\Admin\Resources\SpecialiteResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Resources\RelationManagers\RelationManager;


class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';
    protected static ?string $title = 'Modules de la spécialité';

    public function form(Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nom')->label('Nom du module')->required()->maxLength(191),
        ]);
    }

    public function table(Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')->label('Nom du module'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Créé le'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Créer et attacher un module'),
                Tables\Actions\AttachAction::make()->label('Attacher un module existant')
                ->form([
                        Forms\Components\Select::make('recordId')
                            ->label('Module')
                            ->options(\App\Models\Module::all()->pluck('nom', 'id'))
                            ->searchable(),
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Modifier'),
                Tables\Actions\DetachAction::make()->label('Retirer')
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
