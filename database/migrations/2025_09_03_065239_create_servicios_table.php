<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->string('nombre');
            $table->integer('duracion_minutos');
            $table->decimal('precio', 8, 2);
            $table->text('descripcion')->nullable();
            $table->string('color')->nullable();
            $table->foreignId('profesional')->nullable()->constrained('empleados')->onDelete('set null');
            $table->integer('orden')->default(1);
            $table->boolean('activo')->default(true);
            $table->integer('buffer_antes')->default(0);
            $table->integer('buffer_despues')->default(0);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
