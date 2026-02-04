<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Join records with students table by card_id
        $records = DB::table('records')
    ->leftJoin('students', 'records.card_id', '=', 'students.card_id')
    ->select(
        'records.card_id',
        'records.time',
        DB::raw('COALESCE(students.name, records.nama_pelajar) as student_name')
    )
    ->orderBy('records.time', 'desc')
    ->get();


        return view('school.reports.index', compact('records'));
    }
}
