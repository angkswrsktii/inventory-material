<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'part_name', 'part_no', 'customer',
        'specification', 'unit',
        'supplier', 'panjang_material', 'panjang_part', 'bq',
        'minimum_stock', 'max_stock', 'current_stock',
        'description', 'is_active',
    ];

    protected $casts = [
        'minimum_stock'    => 'decimal:2',
        'max_stock'        => 'decimal:2',
        'current_stock'    => 'decimal:2',
        'panjang_material' => 'decimal:2',
        'panjang_part'     => 'decimal:2',
        'bq'               => 'decimal:4',
        'is_active'        => 'boolean',
    ];

    /**
     * Status stok berdasarkan min/max stock.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= 0)                        return 'empty';
        if ($this->minimum_stock && $this->current_stock <= $this->minimum_stock) return 'danger';
        if ($this->max_stock && $this->current_stock > $this->max_stock)          return 'over';
        if ($this->minimum_stock && $this->current_stock <= ($this->minimum_stock * 1.2)) return 'warning';
        return 'aman';
    }

    // ── Scopes ───────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock')
                     ->where('current_stock', '>', 0);
    }

    public function scopeEmptyStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    // ── Relations ────────────────────────────────────────
    public function stockCards()
    {
        return $this->hasMany(StockCard::class);
    }

    public function withdrawalItems()
    {
        return $this->hasMany(WithdrawalItem::class);
    }

    // ── Helpers ──────────────────────────────────────────
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock && $this->current_stock > 0;
    }

    public function getStatusAttribute(): string
    {
        if ($this->current_stock <= 0)                        return 'empty';
        if ($this->current_stock <= $this->minimum_stock)     return 'low';
        return 'normal';
    }
}