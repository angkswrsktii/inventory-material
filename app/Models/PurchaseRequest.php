<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_no',
        'request_date',
        'requested_by_name',
        'department',
        'purpose',
        'notes',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'created_by',
    ];

    protected $casts = [
        'request_date' => 'date',
        'reviewed_at'  => 'datetime',
    ];

    // ── Relations ─────────────────────────────────────────

    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ── Helpers ───────────────────────────────────────────

    public static function generateDocumentNo(): string
    {
        $prefix = 'PR';
        $date   = now()->format('Ymd');
        $last   = static::whereDate('created_at', today())
            ->orderByDesc('id')
            ->first();

        $sequence = $last ? (intval(substr($last->document_no, -4)) + 1) : 1;
        return $prefix . '-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getTotalEstimatedAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return ($item->estimated_price ?? 0) * $item->quantity_requested;
        });
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'Draft',
            'submitted' => 'Menunggu Review',
            'approved'  => 'Disetujui',
            'rejected'  => 'Ditolak',
            'ordered'   => 'Sudah PO',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'muted',
            'submitted' => 'warning',
            'approved'  => 'success',
            'rejected'  => 'danger',
            'ordered'   => 'info',
            default     => 'muted',
        };
    }

    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canSubmit(): bool
    {
        return $this->status === 'draft';
    }

    public function canReview(): bool
    {
        return $this->status === 'submitted';
    }

    public function canMarkOrdered(): bool
    {
        return $this->status === 'approved';
    }
}