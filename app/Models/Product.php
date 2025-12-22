<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'name',
        'price',
        'image',
        'category',
        'has_stock',
        'stock',
    ];

    /**
     * Nilai default untuk atribut tertentu.
     */
    protected $attributes = [
        'category' => 'Makanan',
        'has_stock' => false,
        'stock' => null,
    ];

    /**
     * Accessor untuk mendapatkan URL gambar lengkap.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : 'https://via.placeholder.com/100x100?text=No+Image';
    }

    /**
     * Scope untuk filter berdasarkan kategori (berguna untuk fitur pencarian/filter).
     */
    public function scopeCategory($query, $category)
    {
        if ($category && in_array($category, ['Makanan', 'Minuman'])) {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Scope untuk produk yang masih memiliki stok (bila has_stock = true).
     */
    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->where('has_stock', false)
              ->orWhere('stock', '>', 0);
        });
    }

    /**
     * Kurangi stok otomatis setelah transaksi.
     * Akan aman meski produk tidak menggunakan stok.
     */
    public function reduceStock(int $quantity): void
    {
        if ($this->has_stock && $this->stock !== null) {
            $newStock = max(0, $this->stock - $quantity);
            $this->update(['stock' => $newStock]);
        }
    }
}
