<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\Product;

class MenuAndIngredientSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Bahan Baku (Ingredients)
        $daging = Ingredient::create([
            'name' => 'Daging Sirloin',
            'category' => 'Daging',
            'unit' => 'gram',
            'stock' => 5000, // Stok awal 5000 gram
        ]);

        $sayur = Ingredient::create([
            'name' => 'Sayur Brokoli',
            'category' => 'Sayur',
            'unit' => 'gram',
            'stock' => 2000, // Stok awal 2000 gram
        ]);

        $bumbu = Ingredient::create([
            'name' => 'Bumbu Steak Blackpepper',
            'category' => 'Bumbu',
            'unit' => 'gram',
            'stock' => 1000,
        ]);

        // 2. Buat Data Menu (Products)
        $sirloinSteak = Product::create([
            'name' => 'Sirloin Steak',
            'price' => 150000,
            'category' => 'Steak',
        ]);

        $tenderloinSteak = Product::create([
            'name' => 'Tenderloin Steak',
            'price' => 175000,
            'category' => 'Steak',
        ]);

        // 3. Buat Resep (Menghubungkan Product dengan Ingredient)
        // Resep Sirloin: Butuh 200g Daging, 100g Sayur, 20g Bumbu
        $sirloinSteak->ingredients()->attach([
            $daging->id => ['quantity' => 200],
            $sayur->id => ['quantity' => 100],
            $bumbu->id => ['quantity' => 20],
        ]);

        // Resep Tenderloin: Butuh 150g Daging, 100g Sayur, 20g Bumbu
        $tenderloinSteak->ingredients()->attach([
            $daging->id => ['quantity' => 150],
            $sayur->id => ['quantity' => 100],
            $bumbu->id => ['quantity' => 20],
        ]);
    }
}