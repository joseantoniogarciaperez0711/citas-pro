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
        Schema::create('cita_servicio', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();

            $table->foreignId('servicio_id')->nullable()->constrained('servicios')->nullOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete();

            $table->string('nombre_servicio_snapshot');
            $table->integer('precio_servicio_snapshot');     // misma unidad que servicios.precio
            $table->integer('duracion_minutos_snapshot');

            $table->integer('cantidad')->default(1);
            $table->integer('descuento')->default(0);

            $table->integer('orden')->default(1);
            $table->string('estado', 40)->default('pendiente');
            $table->text('notas')->nullable();

            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cita_servicio');
    }
};
