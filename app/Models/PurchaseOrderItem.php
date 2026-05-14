<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $table = 't_purchase_order_items';

    protected $fillable = [
        't_purchase_order_id',
        'm_material_id',
        'quantity',
        'price',
        'unit',
        'notes',
        'price_per_qty',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price'    => 'decimal:2',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 't_purchase_order_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'm_material_id');
    }
}