<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionQc extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_no', 'qc_date', 'withdrawal_card_id', 'gedung',
        'qty_produksi', 'qty_sfg', 'qty_ng', 'ng_notes',
        'status', 'notes', 'created_by', 'approved_by', 'approved_at',
    ];

    protected $casts = [
        'qc_date'      => 'date',
        'approved_at'  => 'datetime',
        'qty_produksi' => 'decimal:2',
        'qty_sfg'      => 'decimal:2',
        'qty_ng'       => 'decimal:2',
    ];

    public function withdrawalCard()
    {
        return $this->belongsTo(WithdrawalCard::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'    => 'Draft',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'    => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default    => 'muted',
        };
    }

    public function getNgPercentageAttribute(): float
    {
        if (!$this->qty_produksi) return 0;
        return round(($this->qty_ng / $this->qty_produksi) * 100, 2);
    }

    public static function generateDocumentNo(): string
    {
        $today  = now()->format('Ymd');
        $prefix = "QC-{$today}";
        $last   = static::withTrashed()
            ->where('document_no', 'like', "{$prefix}%")
            ->orderByDesc('id')->first();
        $seq = $last ? ((int) substr($last->document_no, -4)) + 1 : 1;
        return $prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}