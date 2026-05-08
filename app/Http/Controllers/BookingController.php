<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create(Vehicle $vehicle)
{
    $bookedDates = Booking::where('vehicle_id', $vehicle->id)
        ->whereIn('status', ['pending', 'confirmed'])
        ->get([
            'start_date',
            'end_date'
        ]);

    return view('bookings.create', compact(
        'vehicle',
        'bookedDates'
    ));
}

    public function store(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        $overlappingBooking = Booking::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($request) {

                $query->whereBetween('start_date', [
                    $request->start_date,
                    $request->end_date
                ])

                ->orWhereBetween('end_date', [
                    $request->start_date,
                    $request->end_date
                ])

                ->orWhere(function ($query2) use ($request) {

                    $query2->where('start_date', '<=', $request->start_date)
                        ->where('end_date', '>=', $request->end_date);

                });

            })
            ->exists();

        if ($overlappingBooking) {

            return back()->withErrors([
                'dates' => 'Vehicle already booked for selected dates.'
            ]);

        }



        $totalDays = $start->diffInDays($end) + 1;
        $subtotal = $vehicle->price_per_day * $totalDays;
        $commissionAmount = $subtotal * 0.10;
        $ownerPayout = $subtotal - $commissionAmount;

        Booking::create([
            'vehicle_id' => $vehicle->id,
            'customer_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'daily_rate' => $vehicle->price_per_day,
            'subtotal' => $subtotal,
            'commission_amount' => $commissionAmount,
            'owner_payout_amount' => $ownerPayout,
            'total_amount' => $subtotal,
            'status' => 'pending',
            'payment_status' => 'pending',
            'deposit_amount' => $subtotal * 0.30,
            'remaining_balance' => $subtotal * 0.70,
        ]);

        return redirect('/cars')->with('success', 'Booking request submitted.');
    }

        public function myBookings()
    {
        $bookings = Booking::with('vehicle')
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

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors([
                'booking' => 'This booking cannot be cancelled.'
            ]);
        }

        $booking->status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Booking cancelled successfully.');
    }



}