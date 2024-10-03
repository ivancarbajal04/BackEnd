<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            return Category::all();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cargar las categorías.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $errors = $this->validateCategory($request);
        
        if (!empty($errors)) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos',
                'errors' => $errors,
            ], 422);
        }

        $validated = $request->only(['name', 'description']);
        return Category::create($validated);
    }

    public function show($id)
    {
        try {
            return Category::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoría no encontrada.',
            ], 404); 
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cargar la categoría.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $errors = $this->validateCategory($request);

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos',
                'errors' => $errors,
            ], 422);
        }

        try {
            $category = Category::findOrFail($id);
            $validated = $request->only(['name', 'description']);
            $category->update($validated);

            return $category;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoría no encontrada.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la categoría.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $category = Category::findOrFail($id);
            $relatedProductsCount = $category->products()->count();
    
            if ($relatedProductsCount > 0) {
                if ($request->input('force_delete', false)) {

                    $category->products()->delete();

                } else {
                    return response()->json([
                        'message' => 'No se puede eliminar la categoría porque tiene productos relacionados.',
                        'relatedProductsCount' => $relatedProductsCount,
                        'recommendation' => 'Si desea eliminar la categoría, incluya el parámetro "force_delete" en la solicitud.',
                    ], 400); 
                }
            }
    
            $category->delete();
    
            return response()->json(['message' => 'Categoría eliminada con éxito']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoría no encontrada.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la categoría.',
                'error' => $e->getMessage(),
            ], 500); 
        }
    }
    
    

    private function validateCategory(Request $request)
    {
        $errors = [];

        if (!$request->has('name') || trim($request->input('name')) === '') {
            $errors['name'] = 'El campo nombre es obligatorio.';
        } elseif (strlen($request->input('name')) > 255) {
            $errors['name'] = 'El campo nombre no puede exceder 255 caracteres.';
        }

        if ($request->has('description') && strlen($request->input('description')) > 500) {
            $errors['description'] = 'La descripción no puede exceder 500 caracteres.';
        }

        return $errors;
    }
}
