<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOpening extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opening_amount',
        'date',
        'shift_number',
        'is_active',
    ];

    protected $casts = [
        'date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public $timestamps = true;

    // Kasir yang membuka kas
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke closing — HAS ONE
    public function cashClosing()
    {
        return $this->hasOne(CashClosing::class, 'cash_opening_id');
    }
}
