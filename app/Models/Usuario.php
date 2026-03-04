<?php

namespace App\Models;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'email',
        'curp',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
}
