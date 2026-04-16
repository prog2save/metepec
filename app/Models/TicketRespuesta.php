<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketRespuesta extends Model
{
    protected $table = 'ticket_respuestas';
    protected $fillable = [
        'id_ticket', 
        'id_usuario', 
        'contenido', 
        'tipo'
        ];

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
