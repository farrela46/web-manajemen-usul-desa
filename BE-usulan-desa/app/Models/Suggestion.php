<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID',
        'suggestion',
        'status',
        'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
