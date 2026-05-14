<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionQc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_production_qcs';

    protected $fillable = [
        'wo_number',
        'm_part_id',
        't_good_issue_id',
        'checked_by',
        'qc_date',
        'quantity_passed',
        'quantity_failed',
        'quantity_failed_retur',
        'notes',
        'status',
    ];

    protected $casts = [
        'qc_date'               => 'date',
        'quantity_passed'       => 'decimal:2',
        'quantity_failed'       => 'decimal:2',
        'quantity_failed_retur' => 'decimal:2', // Pastikan kolom ini ada di database
    ];

    // Accessor untuk Total Not Good (Gabungan NG Biasa dan NG Retur)
    protected $appends = ['total_ng'];

    public function getTotalNgAttribute()
    {
        return $this->quantity_failed + $this->quantity_failed_retur;
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'm_part_id');
    }

    public function goodIssue()
    {
        return $this->belongsTo(GoodIssue::class, 't_good_issue_id');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}