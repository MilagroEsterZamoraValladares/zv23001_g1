<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Autor extends Model
{
    protected $table = 'autores';

    protected $fillable = [
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'nacionalidad'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function libros(): BelongsToMany
    {
        return $this->belongsToMany(Libro::class, 'libro_autor', 'id_autor', 'id_libro');
    }
}
