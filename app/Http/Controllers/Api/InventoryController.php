<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // Fungsi untuk melihat semua stok bahan baku
    public function index(Request $request)
    {
        // Jika purchasing ingin memfilter (misal: ?category=Daging)
        $query = Ingredient::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $ingredients = $query->get();

        return response()->json([
            'message' => 'List Stok Bahan Baku',
            'data' => $ingredients
        ], 200);
    }
}