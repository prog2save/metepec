<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'id_ciudadano',
        'id_direccion_municipal',
        'id_agente_asignado',
        'id_servicio',
        'asunto',
        'descripcion',
        'tipo_ticket',
        'id_canal',
        'prioridad',
        'estado',
        'direccion_texto',
        'latitud',
        'longitud',
        'observaciones',
        'adjuntos',
        'fecha_resolucion',
    ];
    protected $casts = [
        'adjuntos' => 'array',
        'fecha_resolucion' => 'datetime',
    ];

    public function ciudadano()
    {
        return $this->belongsTo(Ciudadano::class, 'id_ciudadano');
    }

    public function direccionMunicipal()
    {
        return $this->belongsTo(DireccionMunicipal::class, 'id_direccion_municipal');
    }


    public function agente()
    {
        return $this->belongsTo(Usuario::class, 'id_agente_asignado'); // o User::class
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }
    public function canal()
    {
        return $this->belongsTo(CanalIngreso::class, 'id_canal');
    }

    public function estado() 
    {
        return $this->belongsTo(EstadoTicket::class, 'id_estado');
    }
}
