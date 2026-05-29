<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialBatch extends Model
{
    use HasFactory;

    protected $table = 't_material_batches';

    protected $fillable = [
        'load_material_number',
        'm_material_id',
        'm_warehouse_id',
        't_good_receipt_item_id',
        'initial_quantity',
        'remaining_quantity',
        'receipt_date',
    ];

    protected $casts = [
        'initial_quantity'   => 'decimal:2',
        'remaining_quantity' => 'decimal:2',
        'receipt_date'       => 'date',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'm_material_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'm_warehouse_id');
    }

    public function goodReceiptItem()
    {
        return $this->belongsTo(GoodReceiptItem::class, 't_good_receipt_item_id');
    }
}
