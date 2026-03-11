<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoTicket extends Model
{
    use HasFactory;

    protected $table = 'estado_ticket';

    protected $fillable = [
            'categoria',
            'nombre_agente',
            'descripcion_agente',
            'vista_usuario',
            'activo',
            'nombre_usuario',
            'descripcion_usuario',
    ];
}
