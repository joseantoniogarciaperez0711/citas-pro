<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();

            $table->timestamp('fecha')->nullable();

            $table->timestamp('hora_inicio')->nullable();
            $table->timestamp('hora_fin')->nullable();

            $table->string('estado', 40)->default('pendiente');
            $table->text('notas')->nullable();

            // ¡OJO! En la misma unidad que manejes en servicios.precio (entero)
            $table->integer('descuento')->default(0);
            $table->integer('total_snapshot')->default(0);

            // timestamps en español
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
