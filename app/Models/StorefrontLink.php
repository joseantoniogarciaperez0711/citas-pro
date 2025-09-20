<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorefrontLink extends Model
{
    protected $table = 'storefront_links';

    // tus columnas de timestamp en español
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['usuario_id', 'token', 'revocado_en'];
    protected $casts    = ['revocado_en' => 'datetime'];

    // Activo = no revocado
    public function scopeActivo($q)
    {
        return $q->whereNull('revocado_en');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
