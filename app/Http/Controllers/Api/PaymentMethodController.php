<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\PaymentMethod;
use App\Services\PaymentMethodService;

class PaymentMethodController extends Controller
{
    public function getPaymentMethodsList()
    {
        $service = new PaymentMethodService;
        return response()->json(
            $service->paymentMethodsList()
        );
    }
}
