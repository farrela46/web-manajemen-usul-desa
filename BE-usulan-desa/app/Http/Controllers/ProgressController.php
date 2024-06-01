<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Progress;
use Illuminate\Http\Request;
use App\Models\Progress_picture;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'programID' => 'required|exists:programs,id',
            'gambar' => 'required|array',
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        try {
            DB::beginTransaction();

            $progress = Progress::create([
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'programID' => $request->programID,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $imageUrls = [];

            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $gambar) {
                    $path = $gambar->store('public/progress_images');
                    $imageUrl = asset(str_replace('public/', 'storage/', $path));
                    $imageUrls[] = $imageUrl;

                    Progress_picture::create([
                        'path' => $path,
                        'progressID' => $progress->id,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Progress berhasil dibuat',
                'progress' => $progress,
                'imageUrls' => $imageUrls
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function index($programId)
    {
        // Validate to ensure programId exists in the database
        $program = Program::find($programId);

        if (!$program) {
            return response()->json([
                'message' => 'Program not found'
            ], 404);
        }

        $progresses = Progress::where('programID', $programId)
            ->with('progress_picture')
            ->get();

        // Add URLs to the progresses
        $progresses = $progresses->map(function ($progress) {
            $imageUrls = $progress->progress_picture->map(function ($picture) {
                return asset(str_replace('public/', 'storage/', $picture->path));
            });

            return [
                'id_progress' => $progress->id,
                'name' => $progress->name,
                'description' => $progress->description,
                'start_date' => $progress->start_date,
                'end_date' => $progress->end_date,
                'programID' => $progress->programID,
                'created_at' => $progress->created_at,
                'updated_at' => $progress->updated_at,
                'imageUrls' => $imageUrls,
            ];
        });

        return response()->json([
            'id_program' => $program->id,
            'nama_program' => $program->name,
            'progresses' => $progresses
        ]);
    }

    public function getOne($progressID)
    {
        $progress = Progress::where('id', $progressID)
            ->with('progress_picture')
            ->first();

        if (!$progress) {
            return response()->json([
                'message' => 'Progress not found'
            ], 404);
        }

        $program = Program::find($progress->programID);

        $imageUrls = $progress->progress_picture->map(function ($picture) {
            return asset(str_replace('public/', 'storage/', $picture->path));
        });

            $progressData = [
                'id_progress' => $progress->id,
                'name' => $progress->name,
                'description' => $progress->description,
                'start_date' => $progress->start_date,
                'end_date' => $progress->end_date,
                'programID' => $progress->programID,
                'created_at' => $progress->created_at,
                'updated_at' => $progress->updated_at,
                'imageUrls' => $imageUrls,
            ];

        return response()->json([
            'id_program' => $program->id,
            'nama_program' => $program->name,
            'progresses' => $progressData
        ]);
    }
}
