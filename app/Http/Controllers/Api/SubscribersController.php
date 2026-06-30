<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Models\Subscription;
use Hashids\Hashids;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SubscribersController extends Controller
{
    public function list()
    {
        $subscribers = Subscriber::all()->map(function ($subscriber) {
            return [
                'id' => $subscriber->getHashedId(),
                'first_name' => $subscriber->first_name,
                'middle_name' => $subscriber->middle_name,
                'last_name' => $subscriber->last_name,
                'email' => $subscriber->email,
                'birthdate' => $subscriber->birthdate,
                'created_at' => $subscriber->created_at,
                'is_active' => $subscriber->is_active,
            ];
        });

        return response()->json($subscribers);
    }

    public function mikrotikNames($id)
    {
        // return $id;
        $hashids = new Hashids(
            config('hashids.salt'),
            config('hashids.min_length')
        );
        $decoded = $hashids->decode($id);
        if (empty($decoded)) {
            return response()->json([
                'message' => 'Invalid ID'
            ], 404);
        }
        $subscriberId = $decoded[0];
        $microticNames  = Subscription::where('subscriber_id', $subscriberId)
        ->get()
        ->map(function ($subscription) {
            return [
                'subscription_id' => $subscription->getHashedId(),
                'subscriber_id' => $subscription->subscriber_id,
                'mikrotik_name' => $subscription->mikrotik_name,
                'start_date' => $subscription->start_date,
                'status' => $subscription->status,
                'created_at' => $subscription->created_at,
            ];
        });

        return response()->json($microticNames);
        
    }


    public function subscriptionTotals($id, Request $request)
    {
        $hashids = new Hashids(
            config('hashids.salt'),
            config('hashids.min_length')
        );

        $decoded = $hashids->decode($id);

        if (empty($decoded)) {
            return response()->json(['message' => 'Invalid Subscriber'], 404);
        }

        $subscriberId = $decoded[0];

        $mikrotikName = $request->input('mikrotik_name');

        $subscriber = Subscriber::with([
            'subscriptions.plan',
            'subscriptions.payments'
        ])->findOrFail($subscriberId);

        // filter subscriptions by mikrotik_name if provided
        if ($mikrotikName) {
            $subscriber->setRelation(
                'subscriptions',
                $subscriber->subscriptions->where('mikrotik_name', $mikrotikName)
            );
        }

        $service = new \App\Services\BillingService();

        $from = $request->input('from'); // 2026-01
        $to = $request->input('to');     // 2026-06

        return response()->json(
            $service->calculate($subscriber,$from,$to)
        );
    }

    public function generatePdf($subscriptionHash, $from, $to)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($subscriptionHash);

        if (empty($decoded)) {
            abort(404, 'Invalid subscription.');
        }

        $subscription = Subscription::with('plan', 'payments', 'subscriber')
            ->findOrFail($decoded[0]);

        $service = app(\App\Services\BillingService::class);

        $result = $service->generate($subscription, $from, $to);

        $pdf = Pdf::loadView('components.pdf.billing', [
            'billingSummary' => $result['billing_summary'],
            'totals' => $result['totals'],

            'full_name' => $subscription->subscriber->full_name,
            'Mikrotik_Name' => $subscription->mikrotik_name,

            'Month_Cover_From' => $from
                ? Carbon::parse($from . '-01')->startOfMonth()
                : Carbon::parse($subscription->start_date)->startOfMonth(),

            'Month_Cover_To' => $to
                ? Carbon::parse($to . '-01')->endOfMonth()
                : now()->endOfMonth(),
        ]);

        $filename = preg_replace('/[^\pL\pN\s\-\_]/u', '', $subscription->subscriber->full_name) ?: 'billing';

        return $pdf->stream($filename . '.pdf');
    }

}
