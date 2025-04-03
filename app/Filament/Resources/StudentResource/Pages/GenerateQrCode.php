<?php

namespace App\Filament\Resources\StudentResource\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\StudentResource;
use App\Models\Student;

class GenerateQrCode extends Page
{
    protected static string $resource = StudentResource::class;
    protected static string $view = 'filament.resources.student-resource.pages.generate-qr-code';

    public ?Student $student = null; // Allows null if no student is provided

    public function mount(?int $record = null): void
    {
        if ($record) {
            $this->student = Student::find($record);
        }
    }
}
