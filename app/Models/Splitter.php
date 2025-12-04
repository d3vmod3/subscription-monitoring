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
        'napbox_id',
    ];

    public function napbox()
    {
        return $this->belongsTo(Napbox::class);
    }
}
