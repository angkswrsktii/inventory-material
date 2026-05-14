<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mutasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_mutasis';

    protected $fillable = [
        'm_warehouse_id',
        'm_material_id',
        'm_part_id',
        'reference_type',
        'reference_id',
        'type',
        'quantity',
        'balance',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'balance'  => 'decimal:2',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'm_warehouse_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'm_material_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'm_part_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference()
    {
        return $this->morphTo('reference');
    }
}
