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
            'description' => 'required|string',
        ]);

        $suggestion = Suggestion::create([
            'userID' => $user->id,
            'suggestion' => $validatedData['suggestion'],
            'description' => $validatedData['description'],
            'status' => 'pending',
            'date' => Carbon::now(),
        ]);

        if ($suggestion) {
            return response()->json([
                'message' => 'Usulan berhasil dibuat',
                'data' => $suggestion
            ], 201);
        } else {
            return response()->json([
                'message' => 'Server error'
            ], 500);
        }
    }

    public function responseSuggestion(Request $request, $suggestionId)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'suggestion' => 'required|string',
            'description' => 'required|string',
        ]);

        $suggestion = Suggestion::create([
            'userID' => $user->id,
            'suggestion' => $validatedData['suggestion'],
            'description' => $validatedData['description'],
            'status' => 'pending',
            'suggestions_id' => $suggestionId,
            'date' => Carbon::now(),
        ]);

        if ($suggestion) {
            // Ambil data saran induk dengan menggunakan eager loading
            $suggestion->load('parentSuggestion');

            return response()->json([
                'message' => 'Usulan berhasil dibuat',
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
        $user = auth()->user();
        try {
            $userID = $user->id;
            // Query untuk mengambil semua komentar terkait dengan usulan
            $comments = DB::table('comments as c')
                ->select('c.comment', 'c.created_at as tanggal_komen', 'u.nama as nama_komen')
                ->leftJoin('users as u', 'u.id', '=', 'c.userID')
                ->where('c.suggestionID', $id)
                ->orderBy('c.created_at', 'ASC')
                ->get();

            // Query untuk mengambil data usulan beserta jumlah upvote dan downvote
            $suggestion = DB::table('suggestions as b')
                ->select('a.id as id_user', 'b.id as id_suggestion', 'a.nama', 'b.suggestion as saran', 'b.description as deskripsi', 'b.created_at as tanggal', 'v.type as user_vote', DB::raw('IFNULL(u.upvotes, 0) as upvote'), DB::raw('IFNULL(d.downvotes, 0) as downvote'))
                ->leftJoin('users as a', 'a.id', '=', 'b.userID')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as upvotes FROM suggestions_votes WHERE type = "upvote" GROUP BY suggestionID) as u'), 'u.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as downvotes FROM suggestions_votes WHERE type = "downvote" GROUP BY suggestionID) as d'), 'd.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, type FROM suggestions_votes WHERE userID = ' . $userID . ') as v'), 'v.suggestionID', '=', 'b.id')
                ->where('b.id', $id)
                ->first();

            if (!$suggestion) {
                return response()->json(['status' => 'error', 'message' => 'Usulan tidak ditemukan.'], 404);
            }

            return response()->json(['status' => 'success', 'suggestion' => $suggestion, 'comments' => $comments], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // detail usulan admin
    public function DetailAdmin($id)
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
                ->select('a.id as id_user', 'b.id as id_suggestion', 'a.nama', 'b.suggestion as saran', 'b.description as deskripsi', 'b.created_at as tanggal', 'b.status', DB::raw('IFNULL(u.upvotes, 0) as upvote'), DB::raw('IFNULL(d.downvotes, 0) as downvote'), DB::raw('IFNULL(c.comments, 0) as comment'))
                ->leftJoin('users as a', 'a.id', '=', 'b.userID')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as upvotes FROM suggestions_votes WHERE type = "upvote" GROUP BY suggestionID) as u'), 'u.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as downvotes FROM suggestions_votes WHERE type = "downvote" GROUP BY suggestionID) as d'), 'd.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as comments FROM comments GROUP BY suggestionID) as c'), 'c.suggestionID', '=', 'b.id')
                ->where('b.id', $id)
                ->first();

            if (!$suggestion) {
                return response()->json(['status' => 'error', 'message' => 'Usulan tidak ditemukan.'], 404);
            }

            return response()->json(['status' => 'success', 'suggestion' => $suggestion, 'comments' => $comments], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->status !== 'verified') {
            return response()->json([
                'message' => 'Akun kamu belum di verifikasi oleh admin'
            ], 403);
        }

        try {
            $userID = $user->id;

            // Ambil data semua suggestions
            $suggestions = DB::table('suggestions as b')
                ->leftJoin('users as a', 'a.id', '=', 'b.userID')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as upvotes FROM suggestions_votes WHERE type = "upvote" GROUP BY suggestionID) as u'), 'u.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as downvotes FROM suggestions_votes WHERE type = "downvote" GROUP BY suggestionID) as d'), 'd.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as comments FROM comments GROUP BY suggestionID) as c'), 'c.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, type FROM suggestions_votes WHERE userID = ' . $userID . ') as v'), 'v.suggestionID', '=', 'b.id')
                ->select(
                    'b.id',
                    'a.nama as nama',
                    'b.suggestion as saran',
                    'b.description as deskripsi',
                    'b.created_at as tanggal',
                    'v.type as user_vote',
                    DB::raw('IFNULL(u.upvotes, 0) as upvote'),
                    DB::raw('IFNULL(d.downvotes, 0) as downvote'),
                    DB::raw('IFNULL(c.comments, 0) as comment'),
                    'b.suggestions_id as id_asal'
                )
                ->get();

            // Ambil data suggestions yang merupakan tanggapan ke suggestion lain
            $suggestionAsalMap = DB::table('suggestions as b')
                ->leftJoin('users as a', 'a.id', '=', 'b.userID')
                ->select(
                    'b.id',
                    'a.nama as nama',
                    'b.suggestion as saran',
                    'b.description as deskripsi',
                    'b.created_at as tanggal'
                )
                ->get()
                ->keyBy('id');

            // Menggabungkan data
            $response_data = $suggestions->map(function ($suggestion) use ($suggestionAsalMap) {
                return [
                    'id' => $suggestion->id,
                    'nama' => $suggestion->nama,
                    'saran' => $suggestion->saran,
                    'deskripsi' => $suggestion->deskripsi,
                    'tanggal' => $suggestion->tanggal,
                    'user_vote' => $suggestion->user_vote,
                    'upvote' => $suggestion->upvote,
                    'downvote' => $suggestion->downvote,
                    'comment' => $suggestion->comment,
                    'suggestion_asal' => $suggestion->id_asal ? [
                        'id' => $suggestionAsalMap[$suggestion->id_asal]->id,
                        'nama' => $suggestionAsalMap[$suggestion->id_asal]->nama,
                        'saran' => $suggestionAsalMap[$suggestion->id_asal]->saran,
                        'deskripsi' => $suggestionAsalMap[$suggestion->id_asal]->deskripsi,
                        'tanggal' => $suggestionAsalMap[$suggestion->id_asal]->tanggal
                    ] : []
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $response_data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function indexAdmin(Request $request)
    {
        try {
            $status = $request->query('status');

            $query = DB::table('suggestions as b')
                ->leftJoin('users as a', 'a.id', '=', 'b.userID')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as upvotes FROM suggestions_votes WHERE type = "upvote" GROUP BY suggestionID) as u'), 'u.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as downvotes FROM suggestions_votes WHERE type = "downvote" GROUP BY suggestionID) as d'), 'd.suggestionID', '=', 'b.id')
                ->leftJoin(DB::raw('(SELECT suggestionID, COUNT(*) as comments FROM comments GROUP BY suggestionID) as c'), 'c.suggestionID', '=', 'b.id')
                ->select(
                    'b.id',
                    'a.nama as nama',
                    'b.suggestion as saran',
                    'b.description as deskripsi',
                    'b.status as status',
                    'b.updated_at as tanggal',
                    DB::raw('IFNULL(u.upvotes, 0) as upvote'),
                    DB::raw('IFNULL(d.downvotes, 0) as downvote'),
                    DB::raw('IFNULL(c.comments, 0) as comment')
                )
                ->where('b.userID', '!=', 1);
            if (!is_null($status) && $status !== '') {
                $query->where('b.status', $status);
            }

            $suggestions = $query->get();

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

            return response()->json(['status' => 'success', 'message' => 'Komentar berhasil ditambahkan'], 201);
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
                    'b.id as id_suggestion',
                    'b.suggestion as saran',
                    'b.description as deskripsi',
                    'b.updated_at as tanggal',
                    DB::raw('IFNULL(u.upvotes, 0) as upvote'),
                    DB::raw('IFNULL(d.downvotes, 0) as downvote'),
                    DB::raw('IFNULL(c.comments, 0) as comment')
                )
                ->orderBy(DB::raw('IFNULL(u.upvotes, 0)'), 'desc')
                ->get();
            return response()->json(['status' => 'success', 'data' => $suggestions], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function approveSuggestion(Request $request, $id)
    {
        try {
            $suggestion = DB::table('suggestions')->where('id', $id)->first();
            if (!$suggestion) {
                return response()->json(['status' => 'error', 'message' => 'Usulan tidak ditemukan.'], 404);
            }

            $request->validate([
                'target' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            // Pindahkan suggestion ke tabel programs
            $programId = DB::table('programs')->insertGetId([
                'name' => $suggestion->suggestion,
                'description' => $suggestion->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'approved',
                'target' => $request->target,
                'userID' => $suggestion->userID,
                'suggestionID' => $suggestion->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('suggestions')->where('id', $id)->update([
                'status' => 'approved',
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'usulan disetujui dan dipindahkan ke program.', 'program_id' => $programId], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function rejected(Request $request, $id)
    {
        $suggestion = DB::table('suggestions')->where('id', $id)->first();
        if (!$suggestion) {
            return response()->json(['error' => 'User tidak dapat ditemukan.'], 404);
        }

        DB::table('suggestions')->where('id', $id)->update([
            'status' => 'rejected',
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'usulan berhasil ditolak.'], 200);
    }
}