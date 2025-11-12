<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

     protected $fillable = [
        'subscription_id',
        'payment_method_id',
        'reference_number', 
        'paid_at',
        'date_cover_from',
        'date_cover_to', 
        'amount',
        'status',
        'is_discounted',
        'remarks',
        'account_name',
        'is_first_payment',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'is_approved' => 'boolean',
        'is_discounted' => 'boolean',
    ];

    // Relationships
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
