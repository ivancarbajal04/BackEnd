<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
        /**
     * Display a listing of the categories.
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        return Category::create($validated);
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        return Category::findOrFail($id);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return $category;
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::findOrFail($id);
    
        // Verificar si el parámetro de forzar eliminación está presente
        $forceDelete = $request->input('forceDelete', false);
    
        // Verificar si hay productos relacionados con esta categoría
        $relatedProductsCount = $category->products()->count();
    
        if ($relatedProductsCount > 0 && !$forceDelete) {
            return response()->json([
                'message' => "La categoría tiene $relatedProductsCount productos relacionados. ¿Deseas eliminarla de todos modos?",
                'relatedProductsCount' => $relatedProductsCount,
            ], 400);
        }
    
        // Si se permite la eliminación o no hay productos relacionados, elimina la categoría
        $category->delete();
    
        return response()->json(['message' => 'Categoría eliminada exitosamente'], 200);
    }
    
}
