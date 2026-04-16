<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MacroAction extends Model
{
    protected $fillable = [
        'macro_id',
        'field',
        'value',
        'sort_order',
    ];

    public function macro(): BelongsTo
    {
        return $this->belongsTo(Macro::class);
    }

    // Helper para saber si este campo es una relación
    public function isRelationField(): bool
    {
        return in_array($this->field, Macro::RELATION_FIELDS);
    }

    // Etiqueta legible del campo
    public function fieldLabel(): string
    {
        return Macro::FIELDS[$this->field] ?? $this->field;
    }
}