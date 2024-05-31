<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $table = 'progresses';
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'programID',
    ];

    /**
     * Get the program that owns the progress.
     */
    public function program()
    {
        return $this->belongsTo(Program::class, 'programID');
    }

    public function progress_picture()
    {
        return $this->hasMany(Progress_picture::class, 'progressID');
    }
}
