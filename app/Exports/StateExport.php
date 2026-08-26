<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\School;
use App\Models\State;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StateExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        //$schools = School::where('state_id', $this->state_id)->get();
        //$totalPelajarPPD = Student::where('district_id', $ppd->id)->count(); //Count total students in 1 ppd
        // $totalStudents = 0;
        $data = collect([
            ['State Name', 'Total Students', 'Average Percentage (%)'],
        ]);

        $states = State::all();

        $data = $data->merge($states->map(function ($state) {
            $totalStudents = Student::where('state_id', $state->id)->count();

            $attendancesAttend = Attendance::whereHas('student', function (Builder $query) use ($state) {
                $query->where('state_id', $state->id);
            })
                ->where('status', 'attend')
                ->groupBy('student_id')
                ->select('student_id', DB::raw('count(*) as attend_total'))
                ->get();

            $attendancesAbsent = Attendance::whereHas('student', function (Builder $query) use ($state) {
                $query->where('state_id', $state->id);
            })
                ->where('status', 'absent')
                ->groupBy('student_id')
                ->select('student_id', DB::raw('count(*) as absent_total'))
                ->get();

            $attendanceData = [];
            foreach ($attendancesAttend as $attendance) {
                $studentId = $attendance->student_id;
                $attendTotal = $attendance->attend_total;
                $attendanceData[$studentId]['attend_total'] = $attendTotal;
                $attendanceData[$studentId]['absent_total'] = 0; // Initialize absent_total
            }
            
            foreach ($attendancesAbsent as $attendance) {
                $studentId = $attendance->student_id;
                $absentTotal = $attendance->absent_total;
                if (!isset($attendanceData[$studentId])) {
                    $attendanceData[$studentId] = ['attend_total' => 0]; // Initialize attend_total
                }
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

            // dd($state->name);
            return [
                $state->name,
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
