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

    public function citas()
    {
        // citas del empleado vía la tabla cita_servicio
        return $this->belongsToMany(\App\Models\Cita::class, 'cita_servicio', 'empleado_id', 'cita_id')
            ->with('items.empleado')   // trae items y el empleado del item
            ->withPivot('empleado_id') // para saber quién es el del pivot
            ->distinct();
    }
}
