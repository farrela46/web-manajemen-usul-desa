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
            'description' => 'required|string',
            'target' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'

        ]);

        $program = Program::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'approved',
            'target' => $request->target,
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
    public function index()
    {
        $programs = Program::get();

        return response()->json([
            'data'=>$programs
        ]);
    }
    public function detailedProgram($id)
    {
        $program = Program::find($id);

        return response()->json($program);
    }
}
