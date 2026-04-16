<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Macro extends Model
{
    protected $fillable = [
        'name',
        'description',
        'active',
        'created_by',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Campos válidos para las acciones — referencia central
    const FIELDS = [
        'id_ciudadano'          => 'Ciudadano',
        'id_direccion_municipal'=> 'Dirección municipal',
        'id_agente_asignado'    => 'Agente asignado',
        'asunto'                => 'Asunto',
        'descripcion'           => 'Descripción',
        'estado'                => 'Estado',
        'id_canal'              => 'Canal de ingreso',
        'prioridad'             => 'Prioridad',
        'tipo_ticket'           => 'Tipo de ticket',
        'id_servicio'           => 'Servicio',
        'tags'                  => 'Etiquetas',
    ];

    // Campos que guardan un ID (relaciones)
    const RELATION_FIELDS = [
        'id_ciudadano',
        'id_direccion_municipal',
        'id_agente_asignado',
        'id_canal',
        'id_servicio',
    ];

    public function actions(): HasMany
    {
        return $this->hasMany(MacroAction::class)->orderBy('sort_order');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }
}