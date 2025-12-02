<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'location',
        'manager_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Relación con el responsable del departamento
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Relación con los bienes del inventario
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(Inventory::class, 'department_id');
    }

    /**
     * Relación con las tarjetas de responsabilidad
     */
    public function responsibilityCards(): HasMany
    {
        return $this->hasMany(ResponsibilityCard::class, 'department_id');
    }
}
