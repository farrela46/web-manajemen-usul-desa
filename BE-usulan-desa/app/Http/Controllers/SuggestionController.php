<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Comment;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            // Query untuk mengambil semua komentar terkait dengan usulan
            $comments = DB::table('comments as c')
                ->select('c.comment', 'c.created_at as tanggal_komen', 'u.nama as nama_komen')
                ->leftJoin('users as u', 'u.id', '=', 'c.userID')
                ->where('c.suggestionID', $id)
                ->orderBy('c.created_at', 'ASC')
                ->get();

            // Query untuk mengambil data usulan beserta jumlah upvote dan downvote
            $suggestion = DB::table('suggestions as b')
                ->select('a.nama as nama', 'b.suggestion as saran', 'b.updated_at as tanggal', DB::raw('IFNULL(u.upvotes, 0) as upvote'), DB::raw('IFNULL(d.downvotes, 0) as downvote'))
                ->leftJoin('users as a', 'a.id', '=', 'b.userID')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as upvotes FROM suggestions_votes WHERE type = "upvote" GROUP BY suggestionID) as u'), 'u.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as downvotes FROM suggestions_votes WHERE type = "downvote" GROUP BY suggestionID) as d'), 'd.suggestionID', '=', 'b.id')
                ->where('b.id', $id)
                ->first();

            if (!$suggestion) {
                return response()->json(['status' => 'error', 'message' => 'Suggestion not found.'], 404);
            }

            return response()->json(['status' => 'success', 'suggestion' => $suggestion, 'comments' => $comments], 200);
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

    public function addComment(Request $request, $suggestionId)
    {
        try {
            // Validasi input
            $request->validate([
                'comment' => 'required|string'
            ]);

            // Buat komentar baru
            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->suggestionID = $suggestionId;
            $comment->userID = Auth::id();
            $comment->save();

            return response()->json(['status' => 'success', 'message' => 'Comment added successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function Upvote($suggestionID)
    {
        try {
            $userID = auth()->user()->id;

            // Cek apakah pengguna sudah memberikan upvote atau downvote sebelumnya
            $existingVote = DB::table('suggestions_votes')
                ->where('suggestionID', $suggestionID)
                ->where('userID', $userID)
                ->first();

            if ($existingVote) {
                if ($existingVote->type === 'upvote') {
                    return response()->json(['status' => 'error', 'message' => 'Kamu sudah upvote usulan ini.'], 400);
                }

                // Jika pengguna sudah memberikan downvote sebelumnya, update ke upvote
                DB::table('suggestions_votes')
                    ->where('suggestionID', $suggestionID)
                    ->where('userID', $userID)
                    ->update(['type' => 'upvote']);

                return response()->json(['status' => 'success', 'message' => 'Vote berhasil diubah ke upvote.'], 200);
            }

            // Jika belum ada vote, tambahkan upvote baru
            DB::table('suggestions_votes')->insert([
                'suggestionID' => $suggestionID,
                'userID' => $userID,
                'type' => 'upvote',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Upvote berhasil.'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function downvote($id)
    {
        try {
            $userID = auth()->user()->id;

            // Cek apakah pengguna sudah memberikan upvote atau downvote sebelumnya
            $existingVote = DB::table('suggestions_votes')
                ->where('suggestionID', $id)
                ->where('userID', $userID)
                ->first();

            if ($existingVote) {
                if ($existingVote->type === 'downvote') {
                    return response()->json(['status' => 'error', 'message' => 'Kamu sudah downvote usulan ini.'], 400);
                }

                // Jika pengguna sudah memberikan upvote sebelumnya, update ke downvote
                DB::table('suggestions_votes')
                    ->where('suggestionID', $id)
                    ->where('userID', $userID)
                    ->update(['type' => 'downvote', 'updated_at' => now()]);

                return response()->json(['status' => 'success', 'message' => 'Vote berhasil diubah ke downvote.'], 200);
            }

            // Jika belum ada vote, tambahkan downvote baru
            DB::table('suggestions_votes')->insert([
                'suggestionID' => $id,
                'userID' => $userID,
                'type' => 'downvote',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Downvote berhasil.'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function rankSuggestion()
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
                ->orderBy(DB::raw('IFNULL(u.upvotes, 0)'), 'desc')
                ->paginate(10);
            return response()->json(['status' => 'success', 'data' => $suggestions], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}