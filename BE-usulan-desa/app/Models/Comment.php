<?php

namespace App\Models;

use App\Models\User;
use App\Models\Suggestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'suggestionID',
        'userID',
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
