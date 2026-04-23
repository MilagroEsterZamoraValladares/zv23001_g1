<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use App\Models\Libro;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    public function index()
    {
        $prestamos = Prestamo::with(['usuario', 'detallePrestamos.libro'])->get();

        return response()->json([
            'success' => true,
            'data' => $prestamos,
            'message' => 'Lista de préstamos obtenida exitosamente'
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_prestamo',
            'estado' => 'sometimes|in:prestado,devuelto,vencido',
            'libros' => 'required|array|min:1',
            'libros.*' => 'exists:libros,id'
        ]);

        foreach ($request->libros as $libroId) {
            $libro = Libro::find($libroId);
            if (!$libro->disponible) {
                return response()->json([
                    'success' => false,
                    'message' => "El libro '{$libro->titulo}' no está disponible"
                ], 400);
            }
        }

        $prestamo = Prestamo::create([
            'usuario_id' => $request->usuario_id,
            'fecha_prestamo' => $request->fecha_prestamo,
            'fecha_devolucion' => $request->fecha_devolucion,
            'estado' => $request->estado ?? 'prestado'
        ]);

        foreach ($request->libros as $libroId) {
            $prestamo->detallePrestamos()->create([
                'id_libro' => $libroId
            ]);

            $libro = Libro::find($libroId);
            $libro->update(['disponible' => false]);
        }

        return response()->json([
            'success' => true,
            'data' => $prestamo->load(['usuario', 'detallePrestamos.libro']),
            'message' => 'Préstamo creado exitosamente'
        ], 201);
    }

    public function show($id)
    {
        $prestamo = Prestamo::with(['usuario', 'detallePrestamos.libro.autores', 'detallePrestamos.libro.categoria'])->find($id);

        if (!$prestamo) {
            return response()->json([
                'success' => false,
                'message' => 'Préstamo no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $prestamo,
            'message' => 'Préstamo obtenido exitosamente'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $prestamo = Prestamo::find($id);

        if (!$prestamo) {
            return response()->json([
                'success' => false,
                'message' => 'Préstamo no encontrado'
            ], 404);
        }

        $request->validate([
            'fecha_devolucion' => 'nullable|date',
            'estado' => 'sometimes|in:prestado,devuelto,vencido'
        ]);

        if ($request->estado === 'devuelto' && $prestamo->estado !== 'devuelto') {
            foreach ($prestamo->detallePrestamos as $detalle) {
                $libro = Libro::find($detalle->id_libro);
                $libro->update(['disponible' => true]);
            }
        }

        $prestamo->update($request->only(['fecha_devolucion', 'estado']));

        return response()->json([
            'success' => true,
            'data' => $prestamo->load(['usuario', 'detallePrestamos.libro']),
            'message' => 'Préstamo actualizado exitosamente'
        ], 200);
    }

    public function destroy($id)
    {
        $prestamo = Prestamo::find($id);

        if (!$prestamo) {
            return response()->json([
                'success' => false,
                'message' => 'Préstamo no encontrado'
            ], 404);
        }

        if ($prestamo->estado !== 'devuelto') {
            foreach ($prestamo->detallePrestamos as $detalle) {
                $libro = Libro::find($detalle->id_libro);
                $libro->update(['disponible' => true]);
            }
        }

        $prestamo->detallePrestamos()->delete();

        $prestamo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Préstamo eliminado exitosamente'
        ], 200);
    }
}
