<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_materials';

    protected $fillable = [
        'm_supplier_id',
        'code',
        'name',
        'specification',
        'unit',
        'panjang_material',
        'description',
        'is_active',
        'project_id',
        'bq',
        'cut_per_day',
    ];

    protected $casts = [
        'panjang_material' => 'decimal:2',
        'is_active'        => 'boolean',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'm_supplier_id');
    }
    public function Project()
    {
        return $this->belongsTo(Project::class, 'm_project');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'm_material_id');
    }
}