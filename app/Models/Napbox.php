<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Napbox extends Model
{
    use HasFactory;

    protected $fillable = [
        'pon_id',
        'napbox_code',
        'name',
        'description',
    ];

    public function pon()
    {
        return $this->belongsTo(PassiveOpticalNetwork::class, 'pon_id');
    }

    public function splitters()
    {

        return $this->hasMany(Splitter::class, 'napbox_id');
    }
}
