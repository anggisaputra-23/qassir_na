<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cash_opening_id',
        'opening_amount',
        'total_sales',
        'total_cash',
        'total_non_cash',
        'total_expenses',
        'expected_cash',
        'actual_cash',
        'difference',
        'shift_number',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // Kasir yang menutup kas
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke cash opening — BELONGS TO
    public function opening()
    {
        return $this->belongsTo(CashOpening::class, 'cash_opening_id');
    }
}
