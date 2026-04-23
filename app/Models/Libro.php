<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Libro extends Model
{
    protected $table = 'libros';

    protected $fillable = [
        'titulo',
        'categoria_id',
        'anio_publicacion',
        'isbn',
        'disponible'
    ];

    protected $casts = [
        'anio_publicacion' => 'integer',
        'disponible' => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(Autor::class, 'libro_autor', 'id_libro', 'id_autor');
    }

    public function detallePrestamos(): HasMany
    {
        return $this->hasMany(DetallePrestamo::class, 'id_libro');
    }
}
