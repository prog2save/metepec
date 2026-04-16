<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketViewCondition extends Model
{
    protected $table = 'ticket_view_conditions';
    protected $fillable = [
        'ticket_view_id',
        'match_type',
        'field',
        'operator',
        'value',
    ];

    // ── Relaciones ──────────────────────────────────────

    public function view(): BelongsTo
    {
        return $this->belongsTo(TicketView::class, 'ticket_view_id');
    }

    // ── Scopes ──────────────────────────────────────────

    public function scopeAll($query)
    {
        return $query->where('match_type', 'all');
    }

    public function scopeAny($query)
    {
        return $query->where('match_type', 'any');
    }
}