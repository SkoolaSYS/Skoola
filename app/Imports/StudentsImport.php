<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class StudentsImport implements ToModel, WithHeadingRow
{
    protected $school_id;

    // Constructor to receive school_id
    public function __construct($school_id)
    {
        $this->school_id = $school_id;
    }

    public function model(array $row)
    {
        // skip empty rows
        if (empty($row['Nama Penuh']) || empty($row['No. Kad Pengenalan'])) {
            return null;
        }

        // skip existing ICs
        $exists = Student::where('ic', $row['No. Kad Pengenalan'])->exists();
        if ($exists) {
            return null;
        }

        // create new student with school_id
        return new Student([
            'name'       => $row['Nama Penuh'] ?? null,
            'ic'         => $row['No. Kad Pengenalan'] ?? null,
            'address'    => $row['Alamat'] ?? null,
            'class_name' => $row['Kelas'] ?? null,
            'school_id'  => $this->school_id,
        ]);
    }
}
