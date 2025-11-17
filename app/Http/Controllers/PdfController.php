<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Hashids\Hashids;

class PdfController extends Controller
{
    public function generatePdf($subscriptionHash, $monthCoverFrom,$monthCoverTo)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($subscriptionHash);

        if (empty($decoded)) {
            abort(404, 'Invalid subscription.');
        }

        $subscriptionId = $decoded[0];

        $subscription = Subscription::with('plan', 'payments', 'subscriber')->findOrFail($subscriptionId);
        
        $from =  $monthCoverFrom ? : null;
        $to = $monthCoverTo ? : null;
        $billingSummary = $this->generateBillingSummary($subscription, $from, $to);

        $pdf = Pdf::loadView('components.pdf.billing', [
            'billingSummary' => $billingSummary,
            'full_name' => $subscription->subscriber->full_name,
            'Mikrotik_Name' => $subscription->mikrotik_name,
            'Month_Cover_From' => $from ? Carbon::parse($from . '-01')->startOfMonth() : Carbon::parse($subscription->start_date)->startOfMonth(),
            'Month_Cover_To' => $to ? Carbon::parse($to . '-01')->endOfMonth() : now()->endOfMonth(),
        ]);

        $filename = preg_replace('/[^\pL\pN\s\-\_]/u', '', $subscription->subscriber->full_name) ?: 'billing';

        return $pdf->stream($filename . '.pdf');
    }

    protected function generateBillingSummary($subscription, $from = null, $to = null)
    {
        $billingSummary = collect();
        $planPrice = abs((float) ($subscription->plan->price ?? 0));

        // Set start and end based on parameters or fallback to defaults
        $billingStart = $from ? Carbon::parse($from . '-01')->startOfMonth() : Carbon::parse($subscription->start_date)->startOfMonth();
        $billingEnd = $to ? Carbon::parse($to . '-01')->endOfMonth() : now()->endOfMonth();

        while ($billingStart->lessThanOrEqualTo($billingEnd)) {
            $monthCover = $billingStart->format('Y-m');

            // Calculate expected amount (prorate first month)
            $subscriptionStart = Carbon::parse($subscription->start_date);
            if ($billingStart->format('Y-m') === $subscriptionStart->format('Y-m') && $subscriptionStart->day !== 1) {
                $daysInMonth = $billingStart->daysInMonth;
                $daysUsed = $daysInMonth - $subscriptionStart->day + 1;
                $expectedAmount = ceil(($planPrice / $daysInMonth) * $daysUsed);
            } else {
                $expectedAmount = $planPrice;
            }

            // Paid & discount
            $paidAmount = $subscription->payments
                ->where('status', 'Approved')
                ->filter(fn($p) => Carbon::parse($p->month_year_cover . '-01')->format('Y-m') === $monthCover)
                ->sum('paid_amount');

            $discountAmount = $subscription->payments
                ->where('status', 'Approved')
                ->filter(fn($p) => Carbon::parse($p->month_year_cover . '-01')->format('Y-m') === $monthCover)
                ->sum('discount_amount');

            $remaining = max($expectedAmount - $paidAmount, 0);
            $status = $remaining - $discountAmount == 0 ? 'Paid' : 'Not Paid';

            $billingSummary->push([
                'month' => $billingStart->format('F Y'),
                'expected_amount' => $expectedAmount,
                'paid_amount' => $paidAmount,
                'status' => $status,
                'discount_amount' => $discountAmount,
                'remaining_balance' => $remaining,
            ]);

            $billingStart->addMonth();
        }

        return $billingSummary;
    }

}
