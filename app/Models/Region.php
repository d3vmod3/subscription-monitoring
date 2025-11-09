<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $primaryKey = 'region_id';
    public $timestamps = false;

    public function provinces()
    {
        return $this->hasMany(Province::class, 'region_id', 'region_id');
    }
}
