<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_prestamo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_prestamo')->constrained('prestamos')->onDelete('cascade');
            $table->foreignId('id_libro')->constrained('libros')->onDelete('cascade');
            $table->timestamps();

            // Evitar duplicados
            $table->unique(['id_prestamo', 'id_libro']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_prestamo');
    }
};
