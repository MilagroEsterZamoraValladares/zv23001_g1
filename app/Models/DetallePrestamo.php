<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePrestamo extends Model
{
    protected $table = 'detalle_prestamo';

    protected $fillable = [
        'id_prestamo',
        'id_libro'
    ];

    public function prestamo(): BelongsTo
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo');
    }

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }
}
