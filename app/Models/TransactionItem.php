<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    /**
     * Semua field yang disimpan saat checkout
     * (sudah termasuk diskon item + siap menampung diskon global per-item)
     */
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',            // subtotal setelah diskon (final)
        'note',
        'discount',            // diskon per-item
        'discount_type',       // tipe diskon per-item
        'global_discount',     // diskon global proporsional yang dikenakan pada item
        'global_discount_type' // percent / nominal
    ];

    /**
     * Relasi ke produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke transaksi
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
