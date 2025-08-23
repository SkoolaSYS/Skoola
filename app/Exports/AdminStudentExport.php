<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\School;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class AdminStudentExport implements FromCollection,WithHeadings //Export list of students in each school (school level view)
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $school_id;
    protected $schoolName;

    public function __construct($school_id, $schoolName)
    {
        $this->school_id = $school_id;
        $this->schoolName = $schoolName;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Set the school name as title
                $event->sheet->mergeCells('A1:C1'); // Adjust column range as needed
                $event->sheet->setCellValue('A1', 'Student Information in ' . $this->schoolName);
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
            ['Student Information in ' . $this->schoolName, ''], // Header row
            ['Student Name', 'Average Percentage (%)'],
        ]);

        $data = $data->merge($students->map(function ($student) {
            $attendancesAttend = Attendance::where('student_id', $student->id)
                ->where('status', 'attend')
                ->count();

            $attendancesAbsent = Attendance::where('student_id', $student->id)
                ->where('status', 'absent')
                ->count();

            $totalAttendances = $attendancesAttend + $attendancesAbsent;
            $attendancePercentage = 0;

            if ($totalAttendances > 0) {
                $attendancePercentage = ($attendancesAttend / $totalAttendances) * 100;
                $attendancePercentage = number_format($attendancePercentage, 1);
            }

            //dd($student, $attendancePercentage,$schoolName->name);
            return [
                $student->name,
                $attendancePercentage,
            ];
        }));
        return $data;
    }

    public function headings(): array
    {
        return [];
    }
}
