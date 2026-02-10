<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Start query
        $query = DB::table('records')
            ->leftJoin('students', 'records.card_id', '=', 'students.card_id')
            ->select(
                'records.card_id',
                'records.time',
                DB::raw('COALESCE(students.name, records.nama_pelajar) as student_name')
            );

        // Apply filters if provided
        if ($request->filled('year')) {
            $query->whereYear('records.time', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('records.time', $request->month);
        }

        if ($request->filled('date')) {
            $query->whereDate('records.time', $request->date);
        }

        // Order by latest first
        $records = $query->orderBy('records.time', 'desc')->get();

        return view('school.reports.index', compact('records'));
    }

}
