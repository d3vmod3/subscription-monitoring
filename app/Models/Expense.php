<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'payment_method_id',
        'title',
        'description',
        'amount',
        'reference_number',
        'status',
        'date_time_issued',
     ];

     protected $casts = [
        'date_time_issued' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
