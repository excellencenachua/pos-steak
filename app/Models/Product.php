<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'category',
    ];

    // Relasi: 1 Product punya banyak Ingredient (melalui tabel product_ingredient)
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredient')
                    ->withPivot('quantity') // Agar data 'quantity' ikut terbaca
                    ->withTimestamps();
    }
}