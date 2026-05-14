<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_parts';

    protected $fillable = [
        'm_customer_id',
        'part_no',
        'part_name',
        'panjang_part',
        'bq',
        'description',
        'is_active',
    ];

    protected $casts = [
        'panjang_part' => 'decimal:2',
        'bq'           => 'decimal:4',
        'is_active'    => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'm_customer_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'm_part_id');
    }
}
