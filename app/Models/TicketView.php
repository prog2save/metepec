<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Usuario;
use App\Models\Ciudadano;

class TicketView extends Model
{
    use SoftDeletes;
    protected $table = 'ticket_views';

    protected $fillable = [
        'title',
        'description',
        'visibility',
        'created_by',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // ── Relaciones ──────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function conditions(): HasMany
    {
        return $this->hasMany(TicketViewCondition::class);
    }

    public function columns(): HasMany
    {
        return $this->hasMany(TicketViewColumn::class)->orderBy('position');
    }

    public function sorts(): HasMany
    {
        return $this->hasMany(TicketViewSort::class);
    }

    // ── Relaciones por bloque de condiciones ────────────

    public function allConditions(): HasMany
    {
        return $this->hasMany(TicketViewCondition::class)->where('match_type', 'all');
    }

    public function anyConditions(): HasMany
    {
        return $this->hasMany(TicketViewCondition::class)->where('match_type', 'any');
    }

    // ── Scopes ──────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeVisibleFor($query, Usuario $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('visibility', 'all_agents')
              ->orWhere(function ($q2) use ($user) {
                  $q2->where('visibility', 'only_me')
                     ->where('created_by', $user->id);
              });
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    public function ciudadano()
    {
        return $this->belongsTo(Ciudadano::class, 'id_ciudadano');
    }

    public function agente()
    {
        return $this->belongsTo(Usuario::class, 'id_agente_asignado'); 
    }
}