<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanalIngreso extends Model
{
    protected $table = 'canales_ingreso';
    protected $fillable = ['nombre'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'canal_id');
    }
}
