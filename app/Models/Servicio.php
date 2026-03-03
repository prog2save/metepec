<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'id_direccion_municipal',
        'nombre_servicio',
    ];

    public function direccionMunicipal()
    {
        return $this->belongsTo(DireccionMunicipal::class, 'id_direccion_municipal');
    }
}
