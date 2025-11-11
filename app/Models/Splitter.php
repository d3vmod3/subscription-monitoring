<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Splitter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function napboxes()
    {
        return $this->hasMany(Napbox::class, 'splitter_id');
    }
}
