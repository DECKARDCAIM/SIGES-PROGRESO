<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appraisal extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'appraisal_number',
        'status',
        'description',
        'conclusion',
        'estimated_value',
        'appraiser_id',
        'appraisal_date',
        'completion_date',
        'document_path',
        'notes',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'appraisal_date' => 'date',
        'completion_date' => 'date',
    ];

    /**
     * Relación con el bien del inventario
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Relación con el perito/evaluador
     */
    public function appraiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'appraiser_id');
    }

    /**
     * Scope para dictámenes pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para dictámenes completados
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
