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
        Schema::create('storefront_links', function (Blueprint $table) {
            $table->id();

            // Dueño del negocio (tu tabla users)
            $table->foreignId('usuario_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Token opaco público (no adivinable)
            $table->ulid('token')->unique();

            // Permitir revocar el enlace sin borrarlo
            $table->timestamp('revocado_en')->nullable();

            // Tus timestamps personalizados
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Si quieres UN enlace por usuario, descomenta:
            // $table->unique('usuario_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storefront_links');
    }
};
