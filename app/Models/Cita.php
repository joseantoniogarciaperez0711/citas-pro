<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    // timestamps personalizados
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id',
        'cliente_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'notas',
        'descuento',
        'total_snapshot',
    ];

    protected $casts = [
        'fecha'       => 'datetime',
        'hora_inicio' => 'datetime',
        'hora_fin'    => 'datetime',
        'creado_en'   => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'usuario_id'); }
    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class, 'cliente_id'); }
    public function items(): HasMany { return $this->hasMany(CitaServicio::class, 'cita_id'); }
}
