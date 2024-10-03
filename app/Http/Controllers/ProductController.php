<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $sortBy = $request->input('sortBy', 'created_at');
            $order = $request->input('order', 'desc');

            $validSortBy = ['name', 'price', 'created_at'];
            if (!in_array($sortBy, $validSortBy)) {
                $sortBy = 'created_at';
            }
            $order = $order === 'asc' ? 'asc' : 'desc';

            return Product::with('category')
                ->orderBy($sortBy, $order)
                ->paginate($perPage);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cargar los productos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $errors = $this->validateProduct($request);
        
        if (!empty($errors)) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos',
                'errors' => $errors,
            ], 422);
        }

        $validated = $request->only(['name', 'description', 'price', 'category_id']);
        return Product::create($validated);
    }

    public function show($id)
    {
        try {
            return Product::with('category')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Producto no encontrado.',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $errors = $this->validateProduct($request);

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos',
                'errors' => $errors,
            ], 422);
        }

        try {
            $product = Product::findOrFail($id);
            $validated = $request->only(['name', 'description', 'price', 'category_id']);
            $product->update(array_filter($validated));

            return $product;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Producto no encontrado.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el producto.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json(['message' => 'Producto eliminado con éxito']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Producto no encontrado.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el producto.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function validateProduct(Request $request)
    {
        $errors = [];

        if (!$request->has('name') || trim($request->input('name')) === '') {
            $errors['name'] = 'El campo nombre es obligatorio.';
        } elseif (strlen($request->input('name')) > 255) {
            $errors['name'] = 'El campo nombre no puede exceder 255 caracteres.';
        }

        if (!$request->has('price') || !is_numeric($request->input('price')) || $request->input('price') <= 0) {
            $errors['price'] = 'El campo precio es obligatorio y debe ser un número mayor a 0.';
        }

        if (!$request->has('category_id') || !$request->input('category_id')) {
            $errors['category_id'] = 'El campo categoria es obligatorio.';
        } elseif (!Category::where('id', $request->input('category_id'))->exists()) {
            $errors['category_id'] = 'El categoria proporcionado no existe.';
        }

        return $errors;
    }
}
