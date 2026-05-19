<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodIssue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_good_issues';

    protected $fillable = [
        'gi_number',
        'm_warehouse_id',
        'm_part_id',
        'm_pic_id',
        'm_project_id',
        'issue_date',
        'purpose',
        'notes',
        'issued_by',
        'received_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'm_warehouse_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'm_part_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function items()
    {
        return $this->hasMany(GoodIssueItem::class, 't_good_issue_id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'm_pic_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'm_project_id');
    }

    // --- Relasi Baru yang Ditambahkan ---
    // Relasi ke ProductionQc (1 Good Issue memiliki 1 hasil QC)
    public function qc()
    {
        return $this->hasOne(ProductionQc::class, 't_good_issue_id');
    }
}