<?php

namespace App\Models;

use App\Models\User;
use App\Models\Suggestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Suggestion_vote extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'userID',
        'suggestionID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function suggestion()
    {
        return $this->belongsTo(Suggestion::class, 'suggestionID');
    }
}
