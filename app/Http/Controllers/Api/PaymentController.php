<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Hashids\Hashids;
use Illuminate\Validation\Rule;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function searchMikrotikName()
    {
        if (strlen($this->subscriber_search) > 1) {
            $search = $this->subscriber_search;

            $this->subscriber_results = Subscription::with('subscriber', 'plan')
                ->where('mikrotik_name', 'like', "%{$search}%")
                ->orWhereHas('subscriber', function ($query) use ($search) {
                    $query->whereRaw(
                        "CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name) LIKE ?",
                        ['%' . $search . '%']
                    );
                })
                ->orWhereHas('subscriber', function ($query) use ($search) {
                    $query->whereRaw(
                        "CONCAT(first_name, ' ' , last_name) LIKE ?",
                        ['%' . $search . '%']
                    );
                })
                ->limit(8)
                ->get()
                ->map(function ($sub) {
                    $fullName = $sub->subscriber
                        ? trim("{$sub->subscriber->first_name} " . ($sub->subscriber->middle_name ?? '') . " {$sub->subscriber->last_name}")
                        : 'N/A';
                    $sub->display_name = "{$fullName} - {$sub->mikrotik_name}";
                    return $sub;
                });
        } else {
            $this->subscriber_results = [];
        }
    }

    public function store($subscriptionHash, Request $request)
    {
        // $validated = $request->validate([
        //     'payment_method_id' => 'required|exists:payment_methods,id',
        //     'reference_number' => 'nullable|string|max:50',
        //     'paid_at' => 'required|date|before_or_equal:today',
        //     'month_year_cover' => 'required|date_format:Y-m',
        //     'paid_amount' => 'required|numeric|min:0',
        //     'is_discounted' => 'boolean',
        //     'discount_amount' => 'nullable|numeric|required_if:is_discounted,true',
        //     'account_name' => 'required|string|max:255',
        // ]);

        // try {
            $hashids = new Hashids(
                config('hashids.salt'),
                config('hashids.min_length')
            );
            $decoded = $hashids->decode($subscriptionHash);
            if (empty($decoded)) {
                return response()->json([
                    'message' => 'Invalid ID'
                ], 404);
            }
            $subscription_id = $decoded[0];

            $decoded_payment_method_id = $hashids->decode($request->payment_method_id);
            if (empty($decoded_payment_method_id)) {
                return response()->json([
                    'message' => 'Payment method not found'
                ], 404);
            }

            $payment_method_id = $decoded_payment_method_id[0];

            $service = new PaymentService();
            $service->create([
                'subscription_id' => $subscription_id,
                'payment_method_id' => $payment_method_id,
                'reference_number' => $request->reference_number,
                'paid_at' => $request->paid_at,
                'month_year_cover' => $request->month_year_cover,
                'paid_amount' => $request->paid_amount,
                'is_discounted' => $request->is_discounted,
                'discount_amount' => $request->discount_amount,
                'remarks' => $request->remarks,
                'account_name' => $request->account_name,
                'expected_amount' => $request->expected_amount,
            ], $request->user()->id );
            

            return response()->json(
                ['message' => 'payment added successfully'],
            );
        // } catch (\Exception $e) {
        //     return response()->json(
        //         ['message' => $e->getMessage()]
        //     );
        // }
    }
}
