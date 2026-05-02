<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'material_id',
        'material_name',
        'material_code',
        'unit',
        'specification',
        'quantity_requested',
        'quantity_approved',
        'estimated_price',
        'item_notes',
    ];

    protected $casts = [
        'quantity_requested' => 'decimal:2',
        'quantity_approved'  => 'decimal:2',
        'estimated_price'    => 'decimal:2',
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function getSubtotalAttribute(): float
    {
        return ($this->estimated_price ?? 0) * $this->quantity_requested;
    }
}