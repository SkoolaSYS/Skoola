<?php

namespace App\Http\Controllers;

use App\Exports\StateExport;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class CountryController extends Controller
{
    public function index()
    {
        $states = State::all();

        foreach ($states as $state) {
            $totalStudents = Student::whereHas('state', function (Builder $query) use ($state) {
                $query->where('id', $state->id);
            })->count();
            $state->totalStudents = $totalStudents;
        }

        return view('country.dashboard', compact('states'));
    }

    public function export()
    {
        return Excel::download(new StateExport(), 'list_of_states.xlsx');
    }
}
