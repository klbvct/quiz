<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'amount',
        'status',
        'payment_id',
        'payment_data'
    ];

    protected $casts = [
        'payment_data' => 'array',
        'amount' => 'decimal:2'
    ];

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
