<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EwalletController extends Controller
{
    public function getParentByEmail(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return response()->json([
                'message' => 'Email is required'
            ], 400);
        }

        $parent = DB::table('users as u')
            ->join('guardians as g', 'g.email', '=', 'u.email')
            ->select(
                'u.id as user_id',
                'u.name',
                'u.email',
                'g.id as guardian_id',
                'g.student_id'
            )
            ->whereRaw('LOWER(u.email) = LOWER(?)', [$email])
            ->first();

        if (!$parent) {
            return response()->json([
                'isSchoolParent' => false
            ]);
        }

        return response()->json([
            'isSchoolParent' => true,
            'parent' => $parent
        ]);
    }
}