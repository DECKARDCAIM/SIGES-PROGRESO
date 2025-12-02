<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResponsibilityCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_number',
        'department_id',
        'issue_date',
        'expiry_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Relación con el departamento
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relación muchos a muchos con usuarios
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'responsibility_card_user')
            ->withPivot('assigned_date', 'removed_date', 'status', 'notes')
            ->withTimestamps();
    }

    /**
     * Usuarios activos en la tarjeta
     */
    public function activeUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('status', 'active');
    }

    /**
     * Traspasos desde esta tarjeta
     */
    public function transfersFrom(): HasMany
    {
        return $this->hasMany(Transfer::class, 'from_card_id');
    }

    /**
     * Traspasos hacia esta tarjeta
     */
    public function transfersTo(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_card_id');
    }
}
