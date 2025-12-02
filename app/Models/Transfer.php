<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_number',
        'inventory_id',
        'from_card_id',
        'to_card_id',
        'delivered_by_user_id',
        'received_by_user_id',
        'transfer_date',
        'status',
        'reason',
        'notes',
        'format_path',
        'signed_document_path',
    ];

    protected $casts = [
        'transfer_date' => 'date',
    ];

    /**
     * Relación con el bien del inventario
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Tarjeta de origen
     */
    public function fromCard(): BelongsTo
    {
        return $this->belongsTo(ResponsibilityCard::class, 'from_card_id');
    }

    /**
     * Tarjeta de destino
     */
    public function toCard(): BelongsTo
    {
        return $this->belongsTo(ResponsibilityCard::class, 'to_card_id');
    }

    /**
     * Usuario que entrega
     */
    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by_user_id');
    }

    /**
     * Usuario que recibe
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }

    /**
     * Scope para traspasos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para traspasos completados
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
