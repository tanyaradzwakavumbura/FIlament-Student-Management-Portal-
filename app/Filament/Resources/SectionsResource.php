<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionsResource\Pages;
use App\Filament\Resources\SectionsResource\RelationManagers;
use App\Models\Section;
use App\Models\Classes;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rules\Unique;

class SectionsResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Academic Management';

    public static function getNavigationbadge(): ?String
    {
        return Section::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('class_id')
                    ->relationship(name: 'class', titleAttribute: 'name')
                    ->preload()
                    ->required()
                    ->label('Class Name')
                    ->searchable(),
                //
                TextInput::make('name')
                    ->required()
                    ->label('Section Name')
                    ->maxLength(255)
                    ->placeholder('Enter Section name')
                    ->unique(ignoreRecord: true, modifyRuleUsing: function ($get, Unique $rule) {
                         return $rule->where('class_id', $get('class_id'));
                }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Section Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('class.name')
                    ->label('Class Name')
                    ->sortable()
                    ->searchable()
                    ->badge(),

                TextColumn::make('students_count')
                    ->counts('students')
                    ->badge(),
                  

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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSections::route('/create'),
            'edit' => Pages\EditSections::route('/{record}/edit'),
        ];
    }
}
