<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodIssueItem extends Model
{
    use HasFactory;

    protected $table = 't_good_issue_items';

    protected $fillable = [
        't_good_issue_id',
        'm_material_id',
        'load_material_number',
        'quantity',
        'unit',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function goodIssue()
    {
        return $this->belongsTo(GoodIssue::class, 't_good_issue_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'm_material_id');
    }
}
