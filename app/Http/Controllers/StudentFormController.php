<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentFormController extends Controller
{
    public function partial(Request $request)
    {
        $index = $request->query('index', 0);
        $prefix = "students[$index]";
        $key = "student-$index";
        return view('student._student-form', compact('prefix', 'key'))->render();
    }
} 