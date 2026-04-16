<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketViewSort extends Model
{
    protected $table = 'ticket_view_sorts';
    protected $fillable = [
        'ticket_view_id',
        'sort_type',
        'column_key',
        'direction',
    ];

    // ── Relaciones ──────────────────────────────────────

    public function view(): BelongsTo
    {
        return $this->belongsTo(TicketView::class, 'ticket_view_id');
    }

    // ── Scopes ──────────────────────────────────────────

    public function scopeGroupBy($query)
    {
        return $query->where('sort_type', 'group_by');
    }

    public function scopeOrderBy($query)
    {
        return $query->where('sort_type', 'order_by');
    }
}