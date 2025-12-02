<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventory';

    protected $fillable = [
        'name',
        'asset_number',
        'sku',
        'description',
        'type',
        'brand',
        'model',
        'serial_number',
        'purchase_price',
        'purchase_date',
        'vendor',
        'quantity',
        'unit',
        'status',
        'condition',
        'notes',
        'images',
        'department_id',
        'qr_path',
    ];

    protected $casts = [
        'images' => 'array',
        'purchase_price' => 'decimal:2',
        'purchase_date' => 'date',
        'quantity' => 'integer',
    ];

    /**
     * Relación con el departamento
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relación con los dictámenes
     */
    public function appraisals(): HasMany
    {
        return $this->hasMany(Appraisal::class);
    }

    /**
     * Relación con los traspasos
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    /**
     * Scope para bienes disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope para bienes asignados
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }
}
