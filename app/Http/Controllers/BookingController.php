<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create(Vehicle $vehicle)
    {
        $vehicle->load('images');

        $bookedDates = Booking::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['pending', 'approved', 'confirmed'])
            ->get(['start_date', 'end_date']);

        return view('bookings.create', compact('vehicle', 'bookedDates'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

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

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        $totalDays = $start->diffInDays($end) + 1;
        $dailyRate = $vehicle->price_per_day;
        $subtotal = $dailyRate * $totalDays;

        $commissionAmount = $subtotal * 0.10;
        $ownerPayoutAmount = $subtotal - $commissionAmount;

        $depositAmount = $subtotal * 0.30;
        $remainingBalance = $subtotal - $depositAmount;

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
            'status' => 'pending',
            'payment_status' => 'pending',
            'deposit_amount' => $depositAmount,
            'remaining_balance' => $remainingBalance,
        ]);

        return redirect()
            ->route('bookings.my')
            ->with('success', 'Booking request submitted successfully.');
    }

    public function myBookings()
    {
        $bookings = Booking::with(['vehicle.images'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.my', compact('bookings'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        if (in_array($booking->status, [
            'completed',
            'rejected',
            'cancelled'
        ])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }


        // Customer Cannot Cancel Booking after pickup date

        if (now()->toDateString() >= $booking->start_date->toDateString()) {
            return back()->with('error', 'Bookings cannot be cancelled after pickup date.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

            return back()->with('success', 'Booking cancelled successfully.');
    }

    public function invoice(Booking $booking)
    {
        if ($booking->customer_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $booking->load(['vehicle', 'customer']);

        return view('bookings.invoice', compact('booking'));
    }

    public function downloadInvoice(Booking $booking)
    {
        if ($booking->customer_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $booking->load(['vehicle', 'customer']);

        $pdf = Pdf::loadView('bookings.invoice-pdf', compact('booking'));

        return $pdf->download('invoice-' . $booking->id . '.pdf');
    }
}