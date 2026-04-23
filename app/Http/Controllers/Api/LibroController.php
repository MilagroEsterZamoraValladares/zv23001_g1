<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{

    public function index()
    {
        $libros = Libro::with(['categoria', 'autores'])->get();

        return response()->json([
            'success' => true,
            'data' => $libros,
            'message' => 'Lista de libros obtenida exitosamente'
        ], 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'categoria_id' => 'required|exists:categorias,id',
            'anio_publicacion' => 'required|integer|min:1500|max:' . date('Y'),
            'isbn' => 'required|string|size:13|unique:libros,isbn',
            'disponible' => 'sometimes|boolean',
            'autores' => 'required|array',
            'autores.*' => 'exists:autores,id'
        ]);

        $libro = Libro::create($request->except('autores'));

        if ($request->has('autores')) {
            $libro->autores()->attach($request->autores);
        }

        return response()->json([
            'success' => true,
            'data' => $libro->load(['categoria', 'autores']),
            'message' => 'Libro creado exitosamente'
        ], 201);
    }


    public function show($id)
    {
        $libro = Libro::with(['categoria', 'autores', 'detallePrestamos.prestamo'])->find($id);

        if (!$libro) {
            return response()->json([
                'success' => false,
                'message' => 'Libro no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $libro,
            'message' => 'Libro obtenido exitosamente'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $libro = Libro::find($id);

        if (!$libro) {
            return response()->json([
                'success' => false,
                'message' => 'Libro no encontrado'
            ], 404);
        }

        $request->validate([
            'titulo' => 'sometimes|required|string|max:200',
            'categoria_id' => 'sometimes|required|exists:categorias,id',
            'anio_publicacion' => 'sometimes|required|integer|min:1500|max:' . date('Y'),
            'isbn' => 'sometimes|required|string|size:13|unique:libros,isbn,' . $id,
            'disponible' => 'sometimes|boolean',
            'autores' => 'sometimes|array',
            'autores.*' => 'exists:autores,id'
        ]);

        $libro->update($request->except('autores'));

        if ($request->has('autores')) {
            $libro->autores()->sync($request->autores);
        }

        return response()->json([
            'success' => true,
            'data' => $libro->load(['categoria', 'autores']),
            'message' => 'Libro actualizado exitosamente'
        ], 200);
    }


    public function destroy($id)
    {
        $libro = Libro::find($id);

        if (!$libro) {
            return response()->json([
                'success' => false,
                'message' => 'Libro no encontrado'
            ], 404);
        }

        if ($libro->detallePrestamos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el libro porque tiene préstamos asociados'
            ], 400);
        }

        $libro->autores()->detach();

        $libro->delete();

        return response()->json([
            'success' => true,
            'message' => 'Libro eliminado exitosamente'
        ], 200);
    }
}
