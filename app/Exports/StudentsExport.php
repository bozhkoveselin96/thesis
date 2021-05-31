<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentsExport implements FromCollection, WithHeadings, ShouldQueue
{
    private array $students;

    /**
     * @param array $students
     */
    public function __construct(array $students)
    {
        $this->students = $students;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        // Add increasing numbers to each student in the array.
        foreach ($this->students as $index => $student) {
            $student->number = ++$index;
        }
        return collect($this->students);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '№',
            'Name',
            'F№',
            'Group',
            'Email',
            'Assigned topic'
        ];
    }
}
