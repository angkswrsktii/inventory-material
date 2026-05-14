<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    use HasFactory;

    protected $table = 't_purchase_request_items';

    protected $fillable = [
        't_purchase_request_id',
        'm_material_id',
        'quantity',
        'unit',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 't_purchase_request_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'm_material_id');
    }
}