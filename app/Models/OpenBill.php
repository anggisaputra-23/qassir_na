<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OpenBill extends Model
{
    protected $fillable = [
        'bill_code',
        'cart',
        'customer_name',
        'order_type',
        'discount_value',
        'discount_type',
        'total',
        'user_id'
    ];

    protected $casts = [
        'cart' => 'array'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
