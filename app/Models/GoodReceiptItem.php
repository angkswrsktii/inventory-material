<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodReceiptItem extends Model
{
    use HasFactory;

    protected $table = 't_good_receipt_items';

    protected $fillable = [
        't_good_receipt_id',
        't_purchase_order_item_id',
        'm_material_id',
        'quantity',
        'unit',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function goodReceipt()
    {
        return $this->belongsTo(GoodReceipt::class, 't_good_receipt_id');
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 't_purchase_order_item_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'm_material_id');
    }
}
