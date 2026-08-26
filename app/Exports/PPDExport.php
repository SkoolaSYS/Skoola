<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\District;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PPDExport implements FromCollection,WithHeadings //Export list of ppd in each ppd (State level view)
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $state_id;
    protected $stateName;
    public function __construct($state_id, $stateName)
    {
        $this->state_id = $state_id;
        $this->stateName = $stateName;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Set the school name as title
                $event->sheet->mergeCells('A1:C1'); // Adjust column range as needed
                $event->sheet->setCellValue('A1', 'PPD Information in State ' . $this->stateName);
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
        $ppd = District::where('state_id', $this->state_id)->get();

        $data = collect([
            ['District Information in ' . $this->stateName, ''], // Header row
            ['PPD Name', 'Total Students', 'Average Percentage (%)'],
        ]);

        $data = $data->merge($ppd->map(function ($ppd) {

            $totalStudents = Student::where('district_id', $ppd->id)->count() ?? 0;

            // Retrieve the attendances for this school
            $attendancesAttend = Attendance::whereHas('student', function (Builder $query) use ($ppd) {
                $query->where('district_id', $ppd->id);
            })
                ->where('status', 'attend')
                ->groupBy('student_id')
                ->select('student_id', DB::raw('count(*) as attend_total'))
                ->get(); // Retrieve the grouped data

            $attendancesAbsent = Attendance::whereHas('student', function (Builder $query) use ($ppd) {
                $query->where('district_id', $ppd->id);
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

            //dd($ppd,$totalStudents,$formattedAttendancePercentageSchool);
            return [
                $ppd->ppd,
                $totalStudents,
                $formattedAttendancePercentageSchool,
            ];
        }));
        //dd($ppds);
        return $data;
    }

    public function headings(): array
    {
        return [];
    }
}
