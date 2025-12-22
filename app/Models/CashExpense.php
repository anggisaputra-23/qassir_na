<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashExpense extends Model
{
    protected $fillable = ['cash_session_id','name','amount'];

    public function session() {
        return $this->belongsTo(CashSession::class);
    }
}
