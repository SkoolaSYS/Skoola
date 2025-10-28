<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReceiveController extends Controller
{
    public function store(Request $request)
    {
        // Example: Log incoming data for debugging
        Log::info('Received data from school:', $request->all());

        // ✅ Validate expected fields (customize to your needs)
        $validated = $request->validate([
            'school_id' => 'required|string',
            'school_name' => 'required|string',
            'data_type' => 'required|string', // e.g. "attendance", "student", etc.
            'payload' => 'required|array',   // The actual data sent
        ]);

        // Example: you can store it in your database
        // e.g. save to a `school_data` table
        // SchoolData::create($validated);

        // Or process the data immediately here...

        return response()->json([
            'status' => 'success',
            'message' => 'Data received successfully',
            'received_at' => now()->toDateTimeString(),
        ]);
    }
}
