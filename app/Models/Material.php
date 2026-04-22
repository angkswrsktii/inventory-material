<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'specification',
        'unit',
        'supplier',
        'minimum_stock',
        'current_stock',
        'description',
        'is_active',
    ];

    protected $casts = [
        'minimum_stock' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function stockCards()
    {
        return $this->hasMany(StockCard::class);
    }

    public function withdrawalItems()
    {
        return $this->hasMany(WithdrawalItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function getStatusAttribute(): string
    {
        if ($this->current_stock <= 0) return 'empty';
        if ($this->current_stock <= $this->minimum_stock) return 'low';
        return 'normal';
    }
}
