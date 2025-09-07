<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClienteArchivo extends Model
{
    protected $fillable = [
        'usuario_id',
        'cliente_id',
        'titulo',
        'nombre_original',
        'mime',
        'tamano',
        'path',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
