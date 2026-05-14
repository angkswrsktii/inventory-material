<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnGi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_returns';

    protected $fillable = [
        'return_number',
        't_good_issue_id',
        't_production_qc_id',
        'return_date',
        'notes',
        'returned_by',
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    public function goodIssue()
    {
        return $this->belongsTo(GoodIssue::class, 't_good_issue_id');
    }

    public function productionQc()
    {
        return $this->belongsTo(ProductionQc::class, 't_production_qc_id');
    }

    public function returner()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function items()
    {
        return $this->hasMany(ReturnGiItem::class, 't_return_id');
    }
}
