<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FormateurResource\Pages;
use App\Filament\Admin\Resources\FormateurResource\RelationManagers;
use App\Models\Formateur;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormateurResource extends Resource
{
    protected static ?string $model = Formateur::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('user_id')
                //     ->relationship('user', 'name')
                //     ->required(),
                Forms\Components\TextInput::make('name')->label('Nom utilisateur')->required(),
                Forms\Components\TextInput::make('nom_complet')->label('Nom complet')->required()->maxLength(30),
                Forms\Components\TextInput::make('email')->label('Email')->email()->required(),
                Forms\Components\TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->visibleOn('create')
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                Forms\Components\TextInput::make('cin')->required()->maxLength(10),
                Forms\Components\TextInput::make('adresse')->maxLength(50),
                Forms\Components\TextInput::make('departement')->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Nom utilisateur')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('cin')->searchable(),
                Tables\Columns\TextColumn::make('nom_complet')->label('Nom complet')->searchable(),
                Tables\Columns\TextColumn::make('adresse')->searchable(),
                Tables\Columns\TextColumn::make('departement')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFormateurs::route('/'),
            'create' => Pages\CreateFormateur::route('/create'),
        ];
    }

}
