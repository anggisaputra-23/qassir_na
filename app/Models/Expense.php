<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cash_opening_id',
        'expense_name', // nama belanja/pengeluaran
        'amount',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public $timestamps = true;

    /**
     * Relasi ke User (kasir yang input pengeluaran)
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Relasi ke CashOpening (shift yang mempunyai pengeluaran)
     */
    public function cashOpening()
    {
        return $this->belongsTo(\App\Models\CashOpening::class, 'cash_opening_id');
    }
}
