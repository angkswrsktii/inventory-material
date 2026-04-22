<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'transaction_date',
        'type',
        'quantity_in',
        'quantity_out',
        'balance',
        'reference_no',
        'source',
        'notes',
        'withdrawal_card_id',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity_in' => 'decimal:2',
        'quantity_out' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function withdrawalCard()
    {
        return $this->belongsTo(WithdrawalCard::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
