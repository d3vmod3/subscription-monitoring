<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
    ];

    /**
     * Relationships
     */

    // A plan can have many subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Helpers
     */

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
}
