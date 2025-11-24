<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'module_scores',
        'recommendations',
        'summary'
    ];

    protected $casts = [
        'module_scores' => 'array',
        'recommendations' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(QuizSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
