<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Progress;
use App\Models\Progress_picture;
use Illuminate\Http\Request;

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
            'gambar' => 'required',
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

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
                $path = $gambar->store('public/menu_images');
                $imageUrl = asset(str_replace('public/', 'storage/', $path));
                $imageUrls[] = $imageUrl;

                Progress_picture::create([
                    'path' => $path,
                    'progressID' => $progress->id,
                ]);
            }
        }

        if ($progress) {
            return response()->json([
                'message' => 'Progress berhasil dibuat',
                'progress' => $progress,
                'imageUrls' => $imageUrls
            ], 201);
        } else {
            return response()->json([
                'message' => 'Server error'
            ], 500);
        }
    }

    public function index($programId)
    {
        // Validasi untuk memastikan programId ada dalam database
        $program = Program::find($programId);

        if (!$program) {
            return response()->json([
                'message' => 'Program not found'
            ], 404);
        }

        // Mengambil semua progress yang terkait dengan programId beserta gambar-gambarnya
        $progresses = $program->progresses()->with('progress_pictures')->get();

        return response()->json([
            'data' => $progresses
        ]);
    }

}
