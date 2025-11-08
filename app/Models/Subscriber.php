<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscriber extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel convention)
    protected $table = 'subscribers';

    // Mass assignable fields
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'birthdate',
        'gender',
        'contact_number',
        'address',
        'status',
    ];

    // Optional: cast fields
    protected $casts = [
        'birthdate' => 'date',
    ];

    // Optional: helper for full name
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
}
