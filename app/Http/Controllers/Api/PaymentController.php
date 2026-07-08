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
use App\Services\AttachmentService;
use Illuminate\Support\Facades\DB;

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

    public function store(string $subscriptionHash, Request $request)
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
        //     'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        // ]);

        $hashids = new Hashids(
            config('hashids.salt'),
            config('hashids.min_length')
        );

        $decodedSubscription = $hashids->decode($subscriptionHash);

        if (empty($decodedSubscription)) {
            return response()->json([
                'message' => 'Invalid subscription.'
            ], 404);
        }

        $decodedPaymentMethod = $hashids->decode($request->payment_method_id);

        if (empty($decodedPaymentMethod)) {
            return response()->json([
                'message' => 'Payment method not found.'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $paymentService = new PaymentService();

            $payment = $paymentService->create([
                'subscription_id'   => $decodedSubscription[0],
                'payment_method_id' => $decodedPaymentMethod[0],
                'reference_number'  => $request->reference_number,
                'paid_at'           => $request->paid_at,
                'month_year_cover'  => $request->month_year_cover,
                'paid_amount'       => $request->paid_amount,
                'is_discounted'     => $request->boolean('is_discounted'),
                'discount_amount'   => $request->discount_amount ?? 0,
                'remarks'           => $request->remarks,
                'account_name'      => $request->account_name,
            ], $request->user()->id);

            if ($request->hasFile('receipt')) {
                $attachmentService = new AttachmentService();

                $attachmentService->upload(
                    $request->file('receipt'),
                    'payment',
                    $payment->id,
                    'receipts'
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Payment added successfully.',
                'data' => $payment,
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create payment.',
                'error' => $e->getMessage(), // Remove this in production
            ], 500);
        }
    }
}
