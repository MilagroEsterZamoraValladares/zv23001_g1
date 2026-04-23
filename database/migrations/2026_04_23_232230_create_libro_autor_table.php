<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libro_autor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_libro')->constrained('libros')->onDelete('cascade');
            $table->foreignId('id_autor')->constrained('autores')->onDelete('cascade');
            $table->timestamps();

            // Evitar duplicados
            $table->unique(['id_libro', 'id_autor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libro_autor');
    }
};
