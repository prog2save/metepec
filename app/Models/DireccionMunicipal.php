<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DireccionMunicipal extends Model
{
    use HasFactory;

    protected $table = 'direccion_municipals';

    protected $fillable = [
        'nombre_direccion',
        'contacto_principal',
        'telefono',
        'email',
        'estatus',
    ];
}
