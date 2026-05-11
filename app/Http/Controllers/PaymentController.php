<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\BookingStatusNotification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Customer Payment History
    |--------------------------------------------------------------------------
    |
    | Shows all payments submitted by the logged-in customer.
    |
    */

    public function index()
    {
        $payments = Payment::with([
                'booking.vehicle',
                'customer',
                'rentalCompany',
            ])
            ->where('customer_id', auth()->id())
            ->latest()
            ->get();

        return view('payments.index', compact('payments'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Manual Payment
    |--------------------------------------------------------------------------
    |
    | Customer submits payment details after the booking is confirmed.
    | Payment remains pending until the rental company confirms receipt.
    |
    */

    public function store(Request $request, Booking $booking)
    {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        |
        | This project uses customer_id on bookings.
        |
        */

        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Status Guard
        |--------------------------------------------------------------------------
        |
        | Customers can only submit payment after company approval/confirmation.
        |
        */

        if (!in_array($booking->status, ['approved', 'confirmed'])) {
            return back()->with('error', 'Payment can only be submitted after booking confirmation.');
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'max:255'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Load Required Relationship
        |--------------------------------------------------------------------------
        */

        $booking->loadMissing('vehicle');

        if (!$booking->vehicle) {
            return back()->with('error', 'Vehicle record not found for this booking.');
        }

        /*
        |--------------------------------------------------------------------------
        | Amount Calculation
        |--------------------------------------------------------------------------
        |
        | Marketplace commission is currently 10%.
        |
        */

        $amount = $booking->total_amount ?? 0;

        $commissionAmount = round($amount * 0.10, 2);

        $companyAmount = round($amount - $commissionAmount, 2);

        /*
        |--------------------------------------------------------------------------
        | Create Or Update Payment
        |--------------------------------------------------------------------------
        |
        | Uses customer_id, not user_id.
        |
        */

        Payment::updateOrCreate(
            [
                'booking_id' => $booking->id,
            ],
            [
                'customer_id' => $booking->customer_id,
                'rental_company_id' => $booking->vehicle->rental_company_id,
                'amount' => $amount,
                'commission_amount' => $commissionAmount,
                'company_amount' => $companyAmount,
                'payment_method' => $validated['payment_method'],
                'payment_gateway' => 'manual',
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Update Booking Payment Status
        |--------------------------------------------------------------------------
        */

        $booking->update([
            'payment_status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('bookings.my')
            ->with('success', 'Payment submitted successfully. Awaiting confirmation by the rental company.');
    }

    /*
    |--------------------------------------------------------------------------
    | Company Marks Payment As Paid
    |--------------------------------------------------------------------------
    |
    | Rental company confirms receipt of payment.
    |
    */

    public function markPaid(Booking $booking)
    {
        /*
        |--------------------------------------------------------------------------
        | Company Authorization
        |--------------------------------------------------------------------------
        */

        $company = auth()->user()->rentalCompany;

        $booking->loadMissing('vehicle', 'customer');

        if (!$company || !$booking->vehicle || $booking->vehicle->rental_company_id !== $company->id) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Amount Calculation
        |--------------------------------------------------------------------------
        */

        $amount = $booking->total_amount ?? 0;

        $commissionAmount = round($amount * 0.10, 2);

        $companyAmount = round($amount - $commissionAmount, 2);

        /*
        |--------------------------------------------------------------------------
        | Create Or Update Payment Record
        |--------------------------------------------------------------------------
        */

        Payment::updateOrCreate(
            [
                'booking_id' => $booking->id,
            ],
            [
                'customer_id' => $booking->customer_id,
                'rental_company_id' => $company->id,
                'amount' => $amount,
                'commission_amount' => $commissionAmount,
                'company_amount' => $companyAmount,
                'payment_method' => 'manual',
                'payment_gateway' => 'manual',
                'status' => 'paid',
                'paid_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Update Booking
        |--------------------------------------------------------------------------
        */

        $booking->update([
            'payment_status' => 'paid',
            'remaining_balance' => 0,
            'paid_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notify Customer
        |--------------------------------------------------------------------------
        */

        if ($booking->customer) {
            $booking->customer->notify(
                new BookingStatusNotification(
                    $booking,
                    'Payment Confirmed',
                    'Your payment has been confirmed by the rental company.'
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return back()->with('success', 'Payment marked as paid successfully.');
    }
}