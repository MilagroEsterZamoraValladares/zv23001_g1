<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');
            $table->integer('anio_publicacion');
            $table->string('isbn', 13)->unique();
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
