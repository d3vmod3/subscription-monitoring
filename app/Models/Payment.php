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
        'account_name',
        'reference_number',
        'paid_at',
        'paid_amount',
        'status',
        'month_year_cover',
        'is_discounted',
        'remarks',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'is_discounted' => 'boolean',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
