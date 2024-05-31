<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress_picture extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'progressID'
    ];

    public function progress()
    {
        return $this->belongsTo(Progress::class, 'progressID');
    }
}
