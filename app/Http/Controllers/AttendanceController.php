<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function show()
    {
        return view('attendance.view');
    }

    public function export()
    {
        return Excel::download(new AttendancesExport, 'student_attendance.xlsx');
    }
}
