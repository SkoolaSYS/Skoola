<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
        // Skip empty rows
        if (empty($row['Nama Penuh']) || empty($row['No. Kad Pengenalan'])) {
            return null;
        }

        // Skip existing ICs
        $exists = Student::where('ic', $row['No. Kad Pengenalan'])->exists();
        if ($exists) {
            return null;
        }

        // Convert Excel date to Y-m-d if exists
        $dob = null;
        if (!empty($row['Tarikh Lahir'])) {
            try {
                $dob = Date::excelToDateTimeObject($row['Tarikh Lahir'])->format('Y-m-d');
            } catch (\ErrorException $e) {
                $dob = Carbon::parse($row['Tarikh Lahir'])->format('Y-m-d');
            }
        }

        // Calculate age if dob exists
        $age = $dob ? Carbon::parse($dob)->age : null;

        // Create new student with mapped columns
        return new Student([
            'name'           => $row['Nama Penuh'] ?? null,
            'ic'             => $row['No. Kad Pengenalan'] ?? null,
            'birth_cert_no'  => $row['No. Sijil Lahir'] ?? null,
            'dob'            => $dob,
            'age'            => $age, 
            'grade'          => $row['Darjah/Tingkatan'] ?? null,
            'class_name'     => $row['Kelas'] ?? null,
            'gender'         => $row['Jantina'] ?? null,
            'race'           => $row['Kaum'] ?? null,
            'religion'       => $row['Agama'] ?? null,
            'nationality'    => $row['Kewarganegaraan'] ?? null,
            'orphan'         => $row['Yatim'] ?? null,
            'oku'            => $row['OKU'] ?? null,
            'address'        => $row['Alamat'] ?? null,
            'school_id'      => $this->school_id,
        ]);
    }
}
