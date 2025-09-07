<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Categoria.php
class categoria extends Model
{
    protected $fillable = ['usuario_id','nombre','descripcion','color','orden','activo','icono'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'categoria_id');
    }
}
