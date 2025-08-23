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
        $parent_id = Auth::user()->id;

        $attendances = Attendance::whereHas('student', function ($query) use ($parent_id) {
            $query->where('parent_id', $parent_id);
        })->get();
        
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
        //dd($this->headings());
        return $data;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Check In',
            'Check Out',
            'Date',
            'Status',
            'Remarks',
            'Remarks Description',
            'Created at',
            'Updated at',
        ];
    }
}
