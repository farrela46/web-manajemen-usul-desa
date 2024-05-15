<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class programs extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'target',
        'userID',
        'suggestionID'
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
