<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{

    public function index()
    {
        $categorias = Categoria::with('libros')->get();

        return response()->json([
            'success' => true,
            'data' => $categorias,
            'message' => 'Lista de categorías obtenida exitosamente'
        ], 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        $categoria = Categoria::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $categoria,
            'message' => 'Categoría creada exitosamente'
        ], 201);
    }


    public function show($id)
    {
        $categoria = Categoria::with('libros')->find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $categoria,
            'message' => 'Categoría obtenida exitosamente'
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        $categoria->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $categoria,
            'message' => 'Categoría actualizada exitosamente'
        ], 200);
    }


    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada'
            ], 404);
        }

        if ($categoria->libros()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la categoría porque tiene libros asociados'
            ], 400);
        }

        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada exitosamente'
        ], 200);
    }
}
