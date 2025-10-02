<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SpecialiteResource\Pages;
use App\Filament\Admin\Resources\SpecialiteResource\RelationManagers\ModulesRelationManager;
use App\Models\Specialite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpecialiteResource extends Resource
{
    protected static ?string $model = Specialite::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->label('Nom de la spécialité')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')->label('Nom de la spécialité'),
                Tables\Columns\TextColumn::make('updated_at')->label('Modifié le')->dateTime(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
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
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record])),
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
        'index' => Pages\ManageSpecialites::route('/'),
        'view' => Pages\ViewSpecialite::route('/{record}'),
        'edit' => Pages\EditSpecialite::route('/{record}/edit'),
    ];
}
}
