<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Booking Form
    |--------------------------------------------------------------------------
    |
    | Displays the booking form for a selected vehicle.
    | Also sends already-booked dates to the Blade view so that unavailable
    | dates can be blocked or greyed out in the date picker.
    |
    */

    public function create(Vehicle $vehicle)
    {
        /*
        |--------------------------------------------------------------------------
        | Load Vehicle Images
        |--------------------------------------------------------------------------
        */

        $vehicle->load('images');

        /*
        |--------------------------------------------------------------------------
        | Fetch Existing Booked Dates
        |--------------------------------------------------------------------------
        |
        | Only active booking statuses should block the calendar.
        | Rejected and cancelled bookings should not block dates.
        |
        */

        $bookedDates = Booking::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['pending', 'approved', 'confirmed'])
            ->get(['start_date', 'end_date']);

        /*
        |--------------------------------------------------------------------------
        | Return Booking Form
        |--------------------------------------------------------------------------
        */

        return view('bookings.create', compact('vehicle', 'bookedDates'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Booking Request
    |--------------------------------------------------------------------------
    |
    | Creates a new customer booking request.
    | At this stage, the booking starts as pending and payment_status also
    | starts as pending.
    |
    */

    public function store(Request $request, Vehicle $vehicle)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Booking Dates
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Check Date Overlap
        |--------------------------------------------------------------------------
        |
        | Prevents two customers from booking the same vehicle on overlapping
        | date ranges.
        |
        */

        $overlap = Booking::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['pending', 'approved', 'confirmed'])
            ->where(function ($query) use ($request) {
                $query->whereDate('start_date', '<=', $request->end_date)
                      ->whereDate('end_date', '>=', $request->start_date);
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'dates' => 'This vehicle is already booked for the selected dates.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Rental Duration
        |--------------------------------------------------------------------------
        */

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        $totalDays = $start->diffInDays($end) + 1;

        /*
        |--------------------------------------------------------------------------
        | Calculate Booking Amounts
        |--------------------------------------------------------------------------
        |
        | Current marketplace model:
        | - Customer pays total booking amount
        | - Platform commission is 10%
        | - Rental company receives remaining 90%
        | - Deposit is 30%
        | - Balance is 70%
        |
        */

        $dailyRate = $vehicle->price_per_day;
        $subtotal = $dailyRate * $totalDays;

        $commissionAmount = round($subtotal * 0.10, 2);
        $ownerPayoutAmount = round($subtotal - $commissionAmount, 2);

        $depositAmount = round($subtotal * 0.30, 2);
        $remainingBalance = round($subtotal - $depositAmount, 2);

        /*
        |--------------------------------------------------------------------------
        | Create Booking
        |--------------------------------------------------------------------------
        |
        | Important:
        | This project uses customer_id, not user_id, on the bookings table.
        |
        */

        Booking::create([
            'vehicle_id' => $vehicle->id,
            'customer_id' => auth()->id(),

            'start_date' => $request->start_date,
            'end_date' => $request->end_date,

            'total_days' => $totalDays,
            'daily_rate' => $dailyRate,

            'subtotal' => $subtotal,
            'commission_amount' => $commissionAmount,
            'owner_payout_amount' => $ownerPayoutAmount,
            'total_amount' => $subtotal,

            'deposit_amount' => $depositAmount,
            'remaining_balance' => $remainingBalance,

            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect To Customer Bookings
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('bookings.my')
            ->with('success', 'Booking request submitted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | My Bookings
    |--------------------------------------------------------------------------
    |
    | Shows all bookings made by the logged-in customer.
    |
    | Loads:
    | - vehicle images for the booking card
    | - payment record for payment status and Pay Now logic
    |
    */

    public function myBookings()
    {
        $bookings = Booking::with([
                'vehicle.images',
                'payment',
            ])
            ->where('customer_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.my', compact('bookings'));
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Booking
    |--------------------------------------------------------------------------
    |
    | Allows a customer to cancel their own booking only if:
    | - they own the booking
    | - the booking is not completed, rejected or already cancelled
    | - the pickup date has not yet arrived
    |
    */

    public function cancel(Booking $booking)
    {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Invalid Cancellation
        |--------------------------------------------------------------------------
        */

        if (in_array($booking->status, ['completed', 'rejected', 'cancelled'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Cancellation After Pickup Date
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::parse($booking->start_date)->startOfDay();

        if (now()->startOfDay()->greaterThanOrEqualTo($startDate)) {
            return back()->with('error', 'Bookings cannot be cancelled after pickup date.');
        }

        /*
        |--------------------------------------------------------------------------
        | Cancel Booking
        |--------------------------------------------------------------------------
        */

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Booking cancelled successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | View Invoice
    |--------------------------------------------------------------------------
    |
    | Allows:
    | - the booking customer to view their invoice
    | - the admin to view any invoice
    |
    */

    public function invoice(Booking $booking)
    {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if ($booking->customer_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Load Invoice Relationships
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'vehicle',
            'customer',
            'payment',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Return Invoice View
        |--------------------------------------------------------------------------
        */

        return view('bookings.invoice', compact('booking'));
    }

    /*
    |--------------------------------------------------------------------------
    | Download Invoice PDF
    |--------------------------------------------------------------------------
    |
    | Generates and downloads a PDF invoice for the customer/admin.
    |
    */

    public function downloadInvoice(Booking $booking)
    {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if ($booking->customer_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Load PDF Data
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'vehicle',
            'customer',
            'payment',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView('bookings.invoice-pdf', compact('booking'));

        /*
        |--------------------------------------------------------------------------
        | Download PDF
        |--------------------------------------------------------------------------
        */

        return $pdf->download('invoice-' . $booking->id . '.pdf');
    }
}