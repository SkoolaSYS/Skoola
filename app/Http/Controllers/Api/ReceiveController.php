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
            'card_id' => 'required|string',
            'time' => 'required|date_format:Y-m-d H:i:s', // adjust format if needed
        ]);

        // Pass data to a Blade view
        return view('receive', [
            'card_id' => $validated['card_id'],
            'time' => $validated['time'],
            'message' => 'Data received successfully',
        ]);
    }
}
