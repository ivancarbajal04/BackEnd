<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Número de elementos por página
        $sortBy = $request->input('sortBy', 'created_at'); // Campo por el que ordenar (por defecto es 'created_at')
        $order = $request->input('order', 'desc'); // Orden (por defecto es 'desc')

        // Validar el campo de ordenación y el orden
        $validSortBy = ['name', 'price', 'created_at']; // Campos válidos para ordenar
        if (!in_array($sortBy, $validSortBy)) {
            $sortBy = 'created_at'; // Valor por defecto si el campo no es válido
        }
        $order = $order === 'asc' ? 'asc' : 'desc'; // Validar que el orden sea 'asc' o 'desc'

        return Product::with('category')
            ->orderBy($sortBy, $order)
            ->paginate($perPage);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        return Product::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validated);

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Producto eliminado con éxito']);
    }
}
