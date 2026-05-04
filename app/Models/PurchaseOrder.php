<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_no',
        'purchase_request_id',
        'order_date',
        'expected_date',
        'supplier_name',
        'supplier_contact',
        'delivery_address',
        'notes',
        'status',
        'total_amount',
        'payment_terms',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'order_date'    => 'date',
        'expected_date' => 'date',
        'approved_at'   => 'datetime',
        'total_amount'  => 'decimal:2',
    ];

    // ── Relations ─────────────────────────────────────────
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Helpers ───────────────────────────────────────────
    public static function generateDocumentNo(): string
    {
        $prefix   = 'PO';
        $date     = now()->format('Ymd');
        $last     = static::whereDate('created_at', today())->orderByDesc('id')->first();
        $sequence = $last ? (intval(substr($last->document_no, -4)) + 1) : 1;
        return $prefix . '-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'Draft',
            'sent'      => 'Terkirim ke Supplier',
            'partial'   => 'Sebagian Diterima',
            'received'  => 'Sudah Diterima Semua',
            'cancelled' => 'Dibatalkan',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'muted',
            'sent'      => 'info',
            'partial'   => 'warning',
            'received'  => 'success',
            'cancelled' => 'danger',
            default     => 'muted',
        };
    }

    public function getTotalAmountComputedAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return ($item->unit_price ?? 0) * $item->quantity_ordered;
        });
    }

    public function canEdit(): bool
    {
        return in_array($this->status, ['draft']);
    }

    public function canSend(): bool
    {
        return $this->status === 'draft';
    }

    public function canCancel(): bool
    {
        return in_array($this->status, ['draft', 'sent']);
    }

    public function isFullyReceived(): bool
    {
        return $this->items->every(fn($item) => $item->quantity_received >= $item->quantity_ordered);
    }
}