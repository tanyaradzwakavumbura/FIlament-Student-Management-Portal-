<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use App\Models\Section;
use App\Models\Classes;
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
use Filament\Forms\Get;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Academic Management';

    public static function getNavigationbadge(): ?String
    {
        return Student::count();
    }

    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('class_id')
                    ->live()
                    ->relationship(name: 'class', titleAttribute: 'name')
                    ->preload()
                    ->required()
                    ->label('Class Name')
                    ->searchable()
                    ->autofocus(),

                Select::make('section_id')
                    ->options(function(Get $get){
                        $classId = $get('class_id');
                        
                        if($classId){
                            return Section::where('class_id', $classId)->pluck('name', 'id')->toArray();
                        }
                    })
                    ->preload()
                    ->required()
                    ->label('Section Name')
                    ->searchable(),
                //
                TextInput::make('name')
                    ->required()
                    ->label('Student Name')
                    ->maxLength(255)
                    ->placeholder('Enter Student name'),

                TextInput::make('email')
                    ->required()
                    ->label('Email')
                    ->maxLength(255)
                    ->placeholder('Enter Student email')
                    ->email()
                    ->unique(),

                
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
                    ->label('Student Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('class.name')
                    ->label('Class Name')
                    ->sortable()
                    ->searchable()
                    ->badge(),
                TextColumn::make('section.name')
                    ->label('Section Name')
                    ->sortable()
                    ->searchable()
                    ->badge(),
            ])
            ->filters([
                //
                Tables\Filters\Filter::make('class-section-filter')
                    ->form([
                        Select::make('class_id')
                            ->label('Filter By Class')
                            ->placeholder('Select Class')
                            ->options(
                                Classes::pluck('name', 'id')->toArray(),
                            ),
                        Select::make('section_id')
                            ->label('Filter By Section')
                            ->placeholder('Select Section')
                            ->options(
                                Section::pluck('name', 'id')->toArray(),
                            )
                    ])->query(function (Builder $query, array $data) {
                        if ($data['class_id']) {
                            $query->where('class_id', $data['class_id']);
                        }
                        if ($data['section_id']) {
                            $query->where('section_id', $data['section_id']);
                        }
                    }),
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Download PDF')
                    ->url(function(Student $student) {
                        return route('student.invoice.generate', $student);
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('Export')
                        ->label("Export Records")

                        ->action(function (Collection $records) {
                            return Excel::download(new StudentsExport($records), 'students.xlsx');
                        })
                       
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageStudents::route('/'),
        ];
    }
}
