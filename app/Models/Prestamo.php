<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestamo extends Model
{
    protected $table = 'prestamos';

    protected $fillable = [
        'usuario_id',
        'fecha_prestamo',
        'fecha_devolucion',
        'estado'
    ];

    protected $casts = [
        'fecha_prestamo' => 'date',
        'fecha_devolucion' => 'date',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detallePrestamos(): HasMany
    {
        return $this->hasMany(DetallePrestamo::class, 'id_prestamo');
    }

    public function libros()
    {
        return $this->belongsToMany(Libro::class, 'detalle_prestamo', 'id_prestamo', 'id_libro');
    }
}
