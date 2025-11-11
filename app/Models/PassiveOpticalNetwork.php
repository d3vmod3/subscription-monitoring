<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PassiveOpticalNetwork extends Model
{
    use HasFactory;

    protected $table = 'pons';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function napboxes()
    {
        return $this->hasMany(Napbox::class, 'pon_id');
    }
}
