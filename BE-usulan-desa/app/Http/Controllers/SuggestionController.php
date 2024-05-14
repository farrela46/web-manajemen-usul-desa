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
        $user = auth()->user();

        if ($user->status !== 'verified') {
            return response()->json([
                'message' => 'Akun kamu belum di verifikasi oleh admin'
            ], 403);
        }

        $validatedData = $request->validate([
            'suggestion' => 'required|string',
        ]);

        $suggestion = Suggestion::create([
            'userID' => $user->id,
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
            $suggestion = DB::select('
            SELECT 
                a.nama as nama, 
                b.suggestion as saran, 
                b.updated_at as tanggal,
                IFNULL(u.upvotes, 0) as upvote, 
                IFNULL(d.downvotes, 0) as downvote, 
                IFNULL(c.comments, 0) as comment
            FROM suggestions as b
            LEFT JOIN users as a ON a.id = b.userID
            LEFT JOIN (
                SELECT suggestionID, COUNT(*) as upvotes 
                FROM suggestions_votes 
                WHERE type = "upvote" 
                GROUP BY suggestionID
            ) as u ON u.suggestionID = b.id
            LEFT JOIN (
                SELECT suggestionID, COUNT(*) as downvotes 
                FROM suggestions_votes 
                WHERE type = "downvote" 
                GROUP BY suggestionID
            ) as d ON d.suggestionID = b.id
            LEFT JOIN (
                SELECT suggestionID, COUNT(*) as comments 
                FROM comments 
                GROUP BY suggestionID
            ) as c ON c.suggestionID = b.id
            WHERE b.id = ?;', [$id]);

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
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as upvotes FROM suggestions_votes WHERE type = "upvote" GROUP BY suggestionID) as u'), 'u.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as downvotes FROM suggestions_votes WHERE type = "downvote" GROUP BY suggestionID) as d'), 'd.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as comments FROM comments GROUP BY suggestionID) as c'), 'c.suggestionID', '=', 'b.id')
                ->select(
                    'a.nama as nama',
                    'b.suggestion as saran',
                    'b.updated_at as tanggal',
                    DB::raw('IFNULL(u.upvotes, 0) as upvote'),
                    DB::raw('IFNULL(d.downvotes, 0) as downvote'),
                    DB::raw('IFNULL(c.comments, 0) as comment')
                )
                ->paginate(10);
            return response()->json(['status' => 'success', 'data' => $suggestions], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Hapus token autentikasi pengguna saat ini
            $request->user()->currentAccessToken()->delete();

            return response()->json(['status' => 'success', 'message' => 'Successfully logged out.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to log out.'], 500);
        }
    }
}