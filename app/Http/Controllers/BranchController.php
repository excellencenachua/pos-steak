<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return response()->json([
            'message' => 'List Data Cabang',
            'data' => $branches
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
        ]);

        $branch = Branch::create($request->all());

        return response()->json([
            'message' => 'Cabang berhasil ditambahkan',
            'data' => $branch
        ], 201);
    }

    public function show(string $id)
    {
        $branch = Branch::find($id);
        if (!$branch) {
            return response()->json(['message' => 'Cabang tidak ditemukan'], 404);
        }
        return response()->json([
            'message' => 'Detail Cabang',
            'data' => $branch
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $branch = Branch::find($id);
        if (!$branch) {
            return response()->json(['message' => 'Cabang tidak ditemukan'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
            'phone' => 'sometimes|required|string|max:20',
        ]);

        $branch->update($request->all());

        return response()->json([
            'message' => 'Cabang berhasil diupdate',
            'data' => $branch
        ], 200);
    }

    public function destroy(string $id)
    {
        $branch = Branch::find($id);
        if (!$branch) {
            return response()->json(['message' => 'Cabang tidak ditemukan'], 404);
        }
        $branch->delete();
        
        return response()->json([
            'message' => 'Cabang berhasil dihapus'
        ], 200);
    }
}