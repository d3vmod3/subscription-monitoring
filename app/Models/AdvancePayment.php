<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    use HasFactory;

    protected $table = 'advance_payments';

    protected $fillable = [
        'subscription_id',
        'payment_method_id',
        'account_name',
        'reference_number',
        'paid_at',
        'paid_amount',
        'status',
        'is_discounted',
        'discount_amount',
        'remarks',
        'user_id',
        'is_used',
    ];

    protected $casts = [
        'is_used' => 'boolean',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
