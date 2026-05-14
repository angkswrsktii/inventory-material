<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'm_stocks';

    protected $fillable = [
        'm_warehouse_id',
        'm_material_id',
        'm_part_id',
        'minimum_stock',
        'max_stock',
        'current_stock',
    ];

    protected $casts = [
        'minimum_stock' => 'decimal:2',
        'max_stock'     => 'decimal:2',
        'current_stock' => 'decimal:2',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'm_warehouse_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'm_material_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'm_part_id');
    }
    
    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= 0) return 'empty';
        if ($this->minimum_stock > 0 && $this->current_stock <= $this->minimum_stock) return 'danger';
        if ($this->max_stock > 0 && $this->current_stock > $this->max_stock) return 'over';
        if ($this->minimum_stock > 0 && $this->current_stock <= ($this->minimum_stock * 1.2)) return 'warning';
        return 'aman';
    }
}
