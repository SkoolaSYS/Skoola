<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class AdminStudentExport implements FromCollection, WithHeadings
{
    protected $school_id;
    protected $schoolName;
    protected $lang;

    public function __construct($school_id, $schoolName, $lang = 'en')
    {
        $this->school_id = $school_id;
        $this->schoolName = $schoolName;
        $this->lang = $lang;

        // Set language
        app()->setLocale($lang);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->mergeCells('A1:B1');

                $event->sheet->setCellValue(
                    'A1',
                    __('messages.student_info_in') . ' ' . $this->schoolName
                );

                $event->sheet->getStyle('A1')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }

    public function collection()
    {
        $students = Student::where('school_id', $this->school_id)->get();

        $data = collect([
            [__('messages.student_info_in') . ' ' . $this->schoolName, ''],
            [
                __('messages.studentname'),
                __('messages.average_percentage'),
            ],
        ]);

        $data = $data->merge(
            $students->map(function ($student) {

                $attend = Attendance::where('student_id', $student->id)
                    ->where('status', 'attend')
                    ->count();

                $absent = Attendance::where('student_id', $student->id)
                    ->where('status', 'absent')
                    ->count();

                $total = $attend + $absent;

                $percentage = 0;

                if ($total > 0) {
                    $percentage = number_format(($attend / $total) * 100, 1);
                }

                return [
                    $student->name,
                    $percentage,
                ];
            })
        );

        return $data;
    }

    public function headings(): array
    {
        return [];
    }
}
