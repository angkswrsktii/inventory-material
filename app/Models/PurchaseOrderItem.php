<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'purchase_request_item_id',
        'material_id',
        'material_name',
        'material_code',
        'unit',
        'specification',
        'quantity_ordered',
        'quantity_received',
        'unit_price',
        'total_price',
        'item_notes',
    ];

    protected $casts = [
        'quantity_ordered'  => 'decimal:2',
        'quantity_received' => 'decimal:2',
        'unit_price'        => 'decimal:2',
        'total_price'       => 'decimal:2',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseRequestItem()
    {
        return $this->belongsTo(PurchaseRequestItem::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function getTotalPriceComputedAttribute(): float
    {
        return ($this->unit_price ?? 0) * $this->quantity_ordered;
    }

    public function getRemainingQtyAttribute(): float
    {
        return max(0, $this->quantity_ordered - $this->quantity_received);
    }
}