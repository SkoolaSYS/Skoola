<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendancesExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $parent_id = Auth::id();

        // Get all student IDs linked to this parent
        $studentIds = \DB::table('parent_student')
            ->where('parent_id', $parent_id)
            ->pluck('student_id');

        // Get attendances for those students
        $attendances = Attendance::whereIn('student_id', $studentIds)->get();

        // Map data for Excel
        $data = $attendances->map(function ($attendance) {
            $student = Student::find($attendance->student_id);
            return [
                $student ? $student->name : 'N/A',
                $attendance->check_in,
                $attendance->check_out,
                $attendance->date,
                $attendance->status,
                $attendance->remarks ?? 'N/A',
                $attendance->remarks_desc ?? 'N/A',
                $attendance->created_at,
                $attendance->updated_at,
            ];
        });

    return $data;
}


    public function headings(): array
{
    return [
        __('messages.studentname'),
        __('messages.checkin'),
        __('messages.checkout'),
        __('messages.date'),
        __('messages.status'),
        __('messages.remarks'),
        __('messages.remarks_desc'),
        __('messages.created_at'),
        __('messages.updated_at'),
    ];
}

}
