<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{

    public function index()
    {
        $autores = Autor::with('libros')->get();

        return response()->json([
            'success' => true,
            'data' => $autores,
            'message' => 'Lista de autores obtenida exitosamente'
        ], 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'nacionalidad' => 'nullable|string|max:50'
        ]);

        $autor = Autor::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $autor,
            'message' => 'Autor creado exitosamente'
        ], 201);
    }


    public function show($id)
    {
        $autor = Autor::with('libros')->find($id);

        if (!$autor) {
            return response()->json([
                'success' => false,
                'message' => 'Autor no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $autor,
            'message' => 'Autor obtenido exitosamente'
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $autor = Autor::find($id);

        if (!$autor) {
            return response()->json([
                'success' => false,
                'message' => 'Autor no encontrado'
            ], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'apellido' => 'sometimes|required|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'nacionalidad' => 'nullable|string|max:50'
        ]);

        $autor->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $autor,
            'message' => 'Autor actualizado exitosamente'
        ], 200);
    }


    public function destroy($id)
    {
        $autor = Autor::find($id);

        if (!$autor) {
            return response()->json([
                'success' => false,
                'message' => 'Autor no encontrado'
            ], 404);
        }

        if ($autor->libros()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el autor porque tiene libros asociados'
            ], 400);
        }

        $autor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Autor eliminado exitosamente'
        ], 200);
    }
}
