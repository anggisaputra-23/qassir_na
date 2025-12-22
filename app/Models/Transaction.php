<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'total',
        'customer_name',
        'order_type',
        'payment_method',
        'status',
        'note',
        'cancel_reason',   // pastikan ini sama dengan nama kolom di database
        'cancelled_at',
        'user_id',
        'discount_value',
        'discount_type',
    ];

    protected $casts = [
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCancelledAtFormattedAttribute()
    {
        return $this->cancelled_at
            ? $this->cancelled_at->format('d-m-Y H:i')
            : null;
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancel');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }
}

