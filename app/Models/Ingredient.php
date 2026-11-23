<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'category',
        'unit',
        'stock',
    ];

    //Relasi: 1 Ingredient bisa dipakai di banyak Product
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredient')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
