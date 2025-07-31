<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\QuizResource\Pages;
use App\Filament\Admin\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->default(30),
                Forms\Components\TextInput::make('note_reussite')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('module_id')
                    ->relationship('module', 'nom')
                    ->required(),
                Forms\Components\Select::make('formateur_id')
                    ->relationship('formateur', 'nom_complet')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('module.nom')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('formateur.nom_complet')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note_reussite')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'success' => 'actif',
                        'warning' => 'inactif',
                        'danger' => 'archivé',
                    ])
                    ->getStateUsing(fn ($record) => $record->status),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ])
            ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make()
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageQuizzes::route('/'),
        ];
    }
}
