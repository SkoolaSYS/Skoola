<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class StudentsTemplateExport implements FromArray
{
    public function array(): array
    {
        // This defines the first row (headers) of the Excel template
        return [
            ['No.', 'Nama Penuh', 'Alamat', 'Kelas', 'No. Kad Pengenalan'],
        ];
    }
}
