<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'nombre',
        'duracion_minutos',
        'precio',
        'descripcion',
        'color',
        'profesional',
        'orden',
        'activo',
        'buffer_antes',
        'buffer_despues'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(categoria::class, 'categoria_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'profesional'); // columna 'profesional'
    }
}
