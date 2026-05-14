<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialType extends Model
{
    protected $table = 'm_material_type';

    // 2. Tentukan kolom yang diizinkan untuk diisi data (Mass Assignment)
    protected $fillable = [
        'material_type_name',
    ];
}
