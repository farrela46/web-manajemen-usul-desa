<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Program;
use App\Models\Suggestion;
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
            'data' => $programs
        ]);
    }
    public function detailedProgram($id)
    {
        $program = Program::find($id);

        return response()->json($program);
    }

    public function update(Request $request, $programID)
    {
        $program = Program::find($programID);

        if (!$program) {
            return response()->json(['error' => 'Program tidak ditemukan.'], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'target' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $program->name = $request->input('name');
        $program->description = $request->input('description');
        $program->start_date = $request->input('start_date');
        $program->end_date = $request->input('end_date');
        $program->target = $request->input('target');
        $program->save();

        return response()->json([
            'message' => 'Program berhasil di update',
            'program' => $program,
        ]);
    }

    public function dashboard()
    {
        try {
            $totalUsulan = Suggestion::whereNull('suggestions_id')->count();
            $totalProgram = Program::count();
            $usulanDisetujui = Suggestion::where('status', 'approved')->count();
            $usulanDitolak = Suggestion::where('status', 'rejected')->count();
            $totalvote = $usulanDitolak + $usulanDisetujui;

            $programs = Program::with([
                'progresses' => function ($query) {
                    $query->latest('updated_at')->limit(1);
                }
            ])
                ->get(['id', 'name'])
                ->map(function ($program) {
                    $latestProgress = $program->progresses->first();
                    return [
                        'name' => $program->name,
                        'progress' => $latestProgress ? $latestProgress->description : 'No progress',
                    ];
                });

            $persetujuan = [
                'disetujui' => $usulanDisetujui / $totalvote * 100,
                'ditolak' => $usulanDitolak / $totalvote * 100
            ];

            return response()->json([
                'total_usulan' => $totalUsulan,
                'total_program' => $totalProgram,
                'usulan_disetujui' => $usulanDisetujui,
                'usulan_ditolak' => $usulanDitolak,
                'programs' => $programs,
                'persetujuan' => $persetujuan
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
