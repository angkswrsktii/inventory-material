<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodReceipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_good_receipts';

    protected $fillable = [
        'gr_number',
        't_purchase_order_id',
        'm_warehouse_id',
        'm_pic_id',
        'receipt_date',
        'delivery_note_number',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'receipt_date' => 'date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 't_purchase_order_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'm_warehouse_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function items()
    {
        return $this->hasMany(GoodReceiptItem::class, 't_good_receipt_id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'm_pic_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'm_project_id');
    }
}