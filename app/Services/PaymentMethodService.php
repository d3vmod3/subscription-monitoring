<?php

namespace App\Services;

use Carbon\Carbon;
use Hashids\Hashids;
use App\Models\PaymentMethod;


class PaymentMethodService
{
    public function paymentMethodsList()
    {
        $hashids = new Hashids(
            config('hashids.salt') . 'payment_method',
            config('hashids.min_length')
        );
        $payment_methods = PaymentMethod::where('is_active',true)
                            ->orderBy('name', 'asc')
                            ->get()
                            ->map(function ($paymentMethod) use ($hashids) {
                                return [
                                    'id' => $hashids->encode($paymentMethod->id),
                                    'name' => $paymentMethod->name,
                                    'is_active' => $paymentMethod->is_active,
                                    // Include any other fields you need
                                ];
                            });
        return $payment_methods;
    }
}