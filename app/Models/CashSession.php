<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashSession extends Model
{
    protected $fillable = [
        'date','cash_in_hand','cash_in_drawer',
        'non_cash_total','sales_total','expense_total',
        'expected_cash','difference','status'
    ];

    public function expenses() {
        return $this->hasMany(CashExpense::class);
    }
}
