<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PassiveOpticalNetwork extends Model
{
    use HasFactory;

    protected $table = 'pons';

    protected $fillable = [
        'sector_id',
        'name',
        'description',
        'is_active',
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function napboxes()
    {
        return $this->hasMany(Napbox::class, 'pon_id');
    }
    
}
