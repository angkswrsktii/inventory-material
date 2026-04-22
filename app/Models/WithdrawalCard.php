<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawalCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_no',
        'withdrawal_date',
        'pic',
        'line',
        'part_name',
        'work_order',
        'notes',
        'status',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'withdrawal_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(WithdrawalItem::class);
    }

    public function stockCards()
    {
        return $this->hasMany(StockCard::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateDocumentNo(): string
    {
        $prefix = 'WD';
        $date = now()->format('Ymd');
        $last = static::whereDate('created_at', today())
            ->orderByDesc('id')
            ->first();
        
        $sequence = $last ? (intval(substr($last->document_no, -4)) + 1) : 1;
        return $prefix . '-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
