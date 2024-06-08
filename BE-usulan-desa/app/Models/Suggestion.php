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
        'suggestions_id',
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

    public function parentSuggestion()
    {
        return $this->belongsTo(Suggestion::class, 'suggestions_id');
    }


    public function childSuggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class, 'suggestions_id');
    }

}
