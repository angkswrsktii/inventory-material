<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'm_project';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
}
