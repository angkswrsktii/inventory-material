<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_purchase_orders';

    protected $fillable = [
        'po_number',
        't_purchase_request_id',
        'm_supplier_id',
        'order_date',
        'expected_delivery_date',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'order_date'             => 'date',
        'expected_delivery_date' => 'date',
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 't_purchase_request_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'm_supplier_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 't_purchase_order_id');
    }
}