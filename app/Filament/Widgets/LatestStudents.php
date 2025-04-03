<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Student;
use Filament\Tables\Columns\TextColumn;

class LatestStudents extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = "full";

    

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // ...
                 Student::query()
                    ->latest()
                    ->take(5)
            
            )
            ->columns([
                // ...
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('class.name')
                    ->label('Class Name')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                TextColumn::make('section.name')
                    ->label('Section Name')
                    ->searchable()
                    ->sortable()
                    ->badge(),

            ]);
    }
}
