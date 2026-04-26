<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'specification', 'unit',
        'supplier', 'minimum_stock', 'current_stock',
        'description', 'is_active',
    ];

    protected $casts = [
        'minimum_stock'  => 'decimal:2',
        'current_stock'  => 'decimal:2',
        'is_active'      => 'boolean',
    ];

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