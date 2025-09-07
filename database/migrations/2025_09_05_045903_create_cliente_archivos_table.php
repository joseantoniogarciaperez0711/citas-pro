<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cliente_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('titulo')->nullable();              // opcional para etiquetar
            $table->string('nombre_original');                 // nombre del archivo subido
            $table->string('mime', 191)->nullable();
            $table->unsignedBigInteger('tamano')->nullable();  // bytes
            $table->string('path');                             // ruta relativa en disk 'public'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_archivos');
    }
};
