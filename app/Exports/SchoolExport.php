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

class SchoolExport implements FromCollection, WithHeadings
{
    protected $ppd_id;
    protected $ppdName;
    protected $lang;

    public function __construct($ppd_id, $ppdName, $lang = 'en')
    {
        $this->ppd_id = $ppd_id;
        $this->ppdName = $ppdName;
        $this->lang = $lang;

        app()->setLocale($lang); // set language
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:C1');

                $event->sheet->setCellValue(
                    'A1',
                    __('messages.student_info_in') . ' ' . $this->ppdName
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
        $schools = School::where('district_id', $this->ppd_id)->get();

        $data = collect([
            [__('messages.student_info_in') . ' ' . $this->ppdName, ''],
            [
                __('messages.school_name'),
                __('messages.total_students'),
                __('messages.average_percentage')
            ],
        ]);

        $data = $data->merge(
            $schools->map(function ($school) {

                $totalStudents = Student::where('school_id', $school->id)->count() ?? 0;

                // Calculate attendance %
                $attendancesAttend = Attendance::whereHas('student', function (Builder $q) use ($school) {
                    $q->where('school_id', $school->id);
                })
                    ->where('status', 'attend')
                    ->groupBy('student_id')
                    ->select('student_id', DB::raw('count(*) as attend_total'))
                    ->get();

                $attendancesAbsent = Attendance::whereHas('student', function (Builder $q) use ($school) {
                    $q->where('school_id', $school->id);
                })
                    ->where('status', 'absent')
                    ->groupBy('student_id')
                    ->select('student_id', DB::raw('count(*) as absent_total'))
                    ->get();

                $attendanceData = [];

                foreach ($attendancesAttend as $attendance) {
                    $attendanceData[$attendance->student_id]['attend_total'] = $attendance->attend_total;
                }

                foreach ($attendancesAbsent as $attendance) {
                    $attendanceData[$attendance->student_id]['absent_total'] = $attendance->absent_total;
                }

                $attendancePercentage = [];

                foreach ($attendanceData as $studentId => $data) {
                    $total = $data['attend_total'] + $data['absent_total'];
                    $attendancePercentage[$studentId] =
                        ($data['attend_total'] / $total) * 100;
                }

                $totalPercentage = array_sum($attendancePercentage);
                $percentage = ($totalStudents > 0)
                    ? ($totalPercentage / ($totalStudents * 100)) * 100
                    : 0;

                return [
                    $school->name,
                    $totalStudents,
                    number_format($percentage, 1),
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
