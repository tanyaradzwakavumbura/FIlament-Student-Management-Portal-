<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classes::factory()
            ->count(10)
            ->sequence(fn ($sequence) => ['name' => 'Class ' . ($sequence->index + 1)])
            ->has(
                Section::factory()
                    ->count(3)
                    ->state(new Sequence(
                        ['name' => 'Section A'],
                        ['name' => 'Section B'],
                        ['name' => 'Section C'] // Added missing section C for completeness
                    ))
                    ->has(
                        Student::factory()
                            ->count(5)
                            ->state(fn (array $attributes, Section $section) => [
                                'class_id' => $section->class_id, // Ensure correct class_id
                                'section_id' => $section->id, // Ensure correct section_id

                            ])
                    )
            )
            ->create();
    }
}
