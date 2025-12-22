<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar minuman
        $minuman = [
            'americano robusta hot',
            'caffe latte arabika',
            'caffe latte robusta',
            'cappucino hot arabaika',
            'cappucino hot robusta',
            'double shot espresso arabika',
            'double shot espresso robusta',
            'one shot espresso arabika',
            'one shot espresso robusta',
            'leci tea ice',
            'lemon tea ice',
            'peach tea ice',
            'kopsu 1',
            'kopsu 2',
            'kopsu 3',
        ];

        // Daftar makanan
        $makanan = [
            'ff',
            'mix platter',
            'nana choco',
            'nana chocochese',
            'onion ring',
            'otak otak',
            'potato wedges',
        ];

        // Insert minuman
        foreach ($minuman as $name) {
            Product::create([
                'name' => ucwords($name),
                'price' => 20000,
                'category' => 'Minuman',
                'has_stock' => false,
                'stock' => null,
                'image' => 'products/' . $name . '.png',
            ]);
        }

        // Insert makanan
        foreach ($makanan as $name) {
            Product::create([
                'name' => ucwords($name),
                'price' => 20000,
                'category' => 'Makanan',
                'has_stock' => false,
                'stock' => null,
                'image' => 'products/' . $name . '.png',
            ]);
        }
    }
}
