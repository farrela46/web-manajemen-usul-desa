<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Suggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID',
        'suggestion',
        'description',
        'status',
        'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function suggestion_vote(): HasMany
    {
        return $this->hasMany(Suggestion_vote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
