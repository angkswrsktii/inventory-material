<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnGiItem extends Model
{
    use HasFactory;

    protected $table = 't_return_items';

    protected $fillable = [
        't_return_id',
        'm_material_id',
        'quantity',
        'unit',
        'notes',
    ];

    public function returnGi()
    {
        return $this->belongsTo(ReturnGi::class, 't_return_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'm_material_id');
    }
}
