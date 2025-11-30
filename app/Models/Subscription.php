<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'plan_id',
        'splitter_id',
        'mikrotik_name',
        'start_date',
        'status',
    ];

    /**
     * Relationships
     */

    // A Subscription belongs to a Subscriber
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    // A Subscription belongs to a Plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // A Subscription belongs to a Passive Optical Network (PON)
    public function splitter()
    {
        return $this->belongsTo(Splitter::class, 'splitter_id');
    }

    // A Subscription has many Payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Helpers
     */

    // Check if subscription is active
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // Get all approved payments
    public function approvedPayments()
    {
        return $this->payments()->where('is_approved', 'approved');
    }

    // Check if paid for a specific month
    public function isPaidForMonth($month, $year): bool
    {
        return $this->approvedPayments()
            ->whereMonth('paid_at', $month)
            ->whereYear('paid_at', $year)
            ->exists();
    }
}
