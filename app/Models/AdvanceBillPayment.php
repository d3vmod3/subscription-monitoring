<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceBillPayment extends Model
{
    protected $fillable = ['subscription_id', 'amount', 'is_used'];

    public function subscription() {
        return $this->belongsTo(Subscription::class);
    }
}