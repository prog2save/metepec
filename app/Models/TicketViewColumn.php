<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketViewColumn extends Model
{
    protected $table = 'ticket_view_columns';
    protected $fillable = [
        'ticket_view_id',
        'column_key',
        'label',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    // ── Relaciones ──────────────────────────────────────

    public function view(): BelongsTo
    {
        return $this->belongsTo(TicketView::class, 'ticket_view_id');
    }

    // ── Helpers ─────────────────────────────────────────

    // Devuelve el label personalizado o el column_key como fallback
    public function getDisplayLabelAttribute(): string
    {
        return $this->label ?? $this->column_key;
    }
}