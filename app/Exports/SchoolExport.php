<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\District;
use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class SchoolExport implements FromCollection, WithHeadings //Export list of schools in each ppd (PPD level view)
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $ppd_id;
    protected $ppdName;

    public function __construct($ppd_id, $ppdName)
    {
        $this->ppd_id = $ppd_id;
        $this->ppdName = $ppdName;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Set the school name as title
                $event->sheet->mergeCells('A1:C1'); // Adjust column range as needed
                $event->sheet->setCellValue('A1', 'Student Information in ' . $this->ppdName);
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
        //$ppd = District::all();
        $schools = School::where('district_id', $this->ppd_id)->get();
        //$totalPelajarPPD = Student::where('district_id', $ppd->id)->count(); //Count total students in 1 ppd
       // $totalStudents = 0;
        $data = collect([
            ['School Information in ' . $this->ppdName, ''], // Header row
            ['School Name', 'Total Students', 'Average Percentage (%)'],
        ]);

        $data = $data->merge($schools->map(function ($school) {

            $totalStudents = Student::where('school_id', $school->id)->count() ?? 0;

            // Retrieve the attendances for this school
            $attendancesAttend = Attendance::whereHas('student', function (Builder $query) use ($school) {
                $query->where('school_id', $school->id);
            })
                ->where('status', 'attend')
                ->groupBy('student_id')
                ->select('student_id', DB::raw('count(*) as attend_total'))
                ->get(); // Retrieve the grouped data

            $attendancesAbsent = Attendance::whereHas('student', function (Builder $query) use ($school) {
                $query->where('school_id', $school->id);
            })
                ->where('status', 'absent')
                ->groupBy('student_id')
                ->select('student_id', DB::raw('count(*) as absent_total'))
                ->get(); // Retrieve the grouped data

            $attendanceData = [];
            foreach ($attendancesAttend as $attendance) {
                $studentId = $attendance->student_id;
                $attendTotal = $attendance->attend_total;
                $attendanceData[$studentId]['attend_total'] = $attendTotal;
            }

            foreach ($attendancesAbsent as $attendance) {
                $studentId = $attendance->student_id;
                $absentTotal = $attendance->absent_total;
                $attendanceData[$studentId]['absent_total'] = $absentTotal;
            }

            $attendancePercentage = [];
            foreach ($attendanceData as $studentId => $data) {
                $totalAttendances = $data['attend_total'] + $data['absent_total'];
                $attendancePercentage[$studentId] = ($data['attend_total'] / $totalAttendances) * 100;
            }

            $totalPercentageAttendancesSchool = array_sum($attendancePercentage);
            $attendancePercentageSchool = ($totalStudents > 0) ? ($totalPercentageAttendancesSchool / ($totalStudents * 100)) * 100 : 0;
            $formattedAttendancePercentageSchool = number_format($attendancePercentageSchool, 1);

            //dd($totalStudents,$formattedAttendancePercentageSchool);
            return [
                $school->name,
                $totalStudents,
                $formattedAttendancePercentageSchool,
            ];
        }));
        return $data;
    }

    public function headings(): array
    {
        return [];
    }
}
