<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ciudadano extends Model
{
    use HasFactory;

    protected $table = 'ciudadanos';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'telefono_principal',
        'telefono_alterno',
        'email',
        'direccion_calle',
        'direccion_numero',
        'direccion_colonia',
        'latitud',
        'longitud',
        'historial_interacciones',
    ];

    protected $casts = [ //castear el json a un array
        'historial_interacciones' => 'array',
    ];
}
