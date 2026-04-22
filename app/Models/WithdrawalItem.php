<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'withdrawal_card_id',
        'material_id',
        'quantity',
        'stock_before',
        'stock_after',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'stock_before' => 'decimal:2',
        'stock_after' => 'decimal:2',
    ];

    public function withdrawalCard()
    {
        return $this->belongsTo(WithdrawalCard::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
