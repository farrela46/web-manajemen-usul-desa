<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|min:8',
        ]);

        $program = Program::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => 'approved',
            'target' => 'general',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if ($program) {
            return response()->json([
                'message' => 'Program berhasil dibuat',
                'data' => $program
            ], 201);
        } else {
            return response()->json([
                'message' => 'Server error'
            ], 500);
        }
    }
    
}
