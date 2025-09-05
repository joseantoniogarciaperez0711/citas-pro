<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = ['usuario_id', 'nombre', 'puesto', 'color', 'status', 'activo'];

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'profesional'); // FK en servicios = profesional
    }
}
