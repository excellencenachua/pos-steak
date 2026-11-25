<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input dari Kasir
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Gunakan Database Transaction agar jika ada error, data tidak setengah-setengah
        DB::beginTransaction();

        try {
            // 2. Buat Invoice Number otomatis (contoh: INV-20231125-1234)
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

            // 3. Buat data Order utama
            $order = Order::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $request->user()->id, // ID kasir yang login
                'total_amount' => 0, // Akan diupdate di bawah
            ]);

            $totalAmount = 0;

            // 4. Looping item yang dipesan oleh Kasir
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                // Simpan ke tabel order_items
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                //(INTEGRASI STOK) 
                // Looping semua bahan baku yang dibutuhkan oleh menu ini
                foreach ($product->ingredients as $ingredient) {
                    $qtyNeeded = $ingredient->pivot->quantity * $item['quantity'];
                    
                    // Cek apakah stok cukup?
                    if ($ingredient->stock < $qtyNeeded) {
                        throw new \Exception("Stok bahan baku {$ingredient->name} tidak cukup!");
                    }

                    // Potong stok di database
                    $ingredient->decrement('stock', $qtyNeeded);
                }
            }

            // 5. Update total harga di tabel Order
            $order->update(['total_amount' => $totalAmount]);

            DB::commit(); // Simpan semua perubahan ke database jika tidak ada error

            return response()->json([
                'message' => 'Transaksi berhasil dan stok telah dipotong',
                'data' => $order->load('items.product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika ada error (misal stok habis)
            return response()->json([
                'message' => 'Transaksi gagal: ' . $e->getMessage()
            ], 400);
        }
    }
}