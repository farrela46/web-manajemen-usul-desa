<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuggestionController extends Controller
{
    public function store(Request $request)
    {
        $userID = auth()->user()->id;

        $validatedData = $request->validate([
            'suggestion' => 'required|string',
        ]);

        $suggestion = Suggestion::create([
            'userID' => $userID,
            'suggestion' => $validatedData['suggestion'],
            'date' => Carbon::now(),
        ]);

        if ($suggestion) {
            return response()->json([
                'message' => 'Suggestion created successfully',
                'data' => $suggestion
            ], 201);
        } else {
            return response()->json([
                'message' => 'Server error'
            ], 500);
        }
    }

    public function getOne($id)
    {
        try {
            $suggestion = DB::select('SELECT a.username as nama, b.suggestion as saran, b.updated_at AS tanggal FROM suggestions as b LEFT JOIN users as a on a.id = b.userID WHERE b.id=?;', [$id]);

            if (!$suggestion) {
                return response()->json(['status' => 'error', 'message' => 'Suggestion not found.'], 404);
            }

            return response()->json(['status' => 'success', 'data' => $suggestion], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            $suggestions = DB::table('suggestions as b')
                ->leftJoin('users as a', 'a.id', '=', 'b.userID')
                ->select('a.username as nama', 'b.suggestion as saran', 'b.updated_at AS tanggal')
                ->paginate(10);
            return response()->json(['status' => 'success', 'data' => $suggestions], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


}
