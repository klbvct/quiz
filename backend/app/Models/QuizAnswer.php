<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'module_number',
        'question_number',
        'answer'
    ];

    public function session()
    {
        return $this->belongsTo(QuizSession::class, 'session_id');
    }
}
