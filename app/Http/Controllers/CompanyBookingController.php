<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\BookingStatusNotification;

class CompanyBookingController extends Controller
{
    private function company()
    {
        $company = auth()->user()->rentalCompany;

        if (!$company) {
            abort(403);
        }

        return $company;
    }

    public function dashboard()
    {
        $company = $this->company();

        $bookings = Booking::with(['vehicle.images', 'customer'])
            ->whereHas('vehicle', function ($query) use ($company) {
                $query->where('rental_company_id', $company->id);
            })
            ->latest()
            ->get();

        $vehicles = Vehicle::with('images')
            ->where('rental_company_id', $company->id)
            ->latest()
            ->get();

        $stats = [
            'total_vehicles' => $vehicles->count(),
            'available_vehicles' => $vehicles->where('available', true)->count(),
            'total_bookings' => $bookings->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'approved_bookings' => $bookings->whereIn('status', ['approved', 'confirmed'])->count(),
            'completed_bookings' => $bookings->where('status', 'completed')->count(),
            'total_revenue' => $bookings
                ->whereIn('status', ['approved', 'confirmed', 'completed'])
                ->sum('owner_payout_amount'),
        ];

        return view('company.dashboard', compact('company', 'vehicles', 'bookings', 'stats'));
    }

    public function index()
    {
        $company = $this->company();

        $bookings = Booking::with(['vehicle.images', 'customer'])
            ->whereHas('vehicle', function ($query) use ($company) {
                $query->where('rental_company_id', $company->id);
            })
            ->latest()
            ->get();

        $stats = [
            'total_bookings' => $bookings->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'approved_bookings' => $bookings->whereIn('status', ['approved', 'confirmed'])->count(),
            'rejected_bookings' => $bookings->where('status', 'rejected')->count(),
            'completed_bookings' => $bookings->where('status', 'completed')->count(),
            'total_revenue' => $bookings
                ->whereIn('status', ['approved', 'confirmed', 'completed'])
                ->sum('owner_payout_amount'),
            'pending_revenue' => $bookings
                ->where('payment_status', 'pending')
                ->sum('remaining_balance'),
        ];

        return view('company.bookings', compact('bookings', 'stats'));
    }

    public function calendar()
    {
        $company = $this->company();

        $bookings = Booking::with(['vehicle', 'customer'])
            ->whereHas('vehicle', function ($query) use ($company) {
                $query->where('rental_company_id', $company->id);
            })
            ->whereIn('status', ['pending', 'approved', 'confirmed', 'completed'])
            ->orderBy('start_date')
            ->get();

        $events = $bookings->map(function ($booking) {
            return [
                'title' => $booking->vehicle->brand . ' ' . $booking->vehicle->model . ' - ' . $booking->customer->name,
                'start' => $booking->start_date,
                'end' => \Carbon\Carbon::parse($booking->end_date)->addDay()->format('Y-m-d'),
                'status' => $booking->status,
                'payment_status' => $booking->payment_status,
                'amount' => $booking->total_amount,
            ];
        });

        return view('company.calendar', compact('bookings', 'events'));
    }

    public function approve(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        if ($booking->customer) {
            $booking->customer->notify(
                new BookingStatusNotification(
                    $booking,
                    'Booking Confirmed',
                    'Your booking has been confirmed by the rental company.'
                )
            );

            $booking->customer->notify(
                new BookingConfirmedNotification($booking)
            );
        }

        return back()->with('success', 'Booking approved successfully.');
    }

    public function reject(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);

        if ($booking->customer) {
            $booking->customer->notify(
                new BookingStatusNotification(
                    $booking,
                    'Booking Rejected',
                    'Unfortunately your booking was rejected.'
                )
            );
        }

        return back()->with('success', 'Booking rejected successfully.');
    }

    public function complete(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if ($booking->customer) {
            $booking->customer->notify(
                new BookingStatusNotification(
                    $booking,
                    'Rental Completed',
                    'Your rental booking has been completed.'
                )
            );
        }

        return back()->with('success', 'Booking marked as completed.');
    }

    public function markPaid(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->update([
            'payment_status' => 'paid',
            'remaining_balance' => 0,
            'paid_at' => now(),
        ]);

        if ($booking->customer) {
            $booking->customer->notify(
                new BookingStatusNotification(
                    $booking,
                    'Payment Received',
                    'Your payment has been marked as received.'
                )
            );
        }

        return back()->with('success', 'Payment marked as paid.');
    }

    protected function authorizeBooking(Booking $booking)
    {
        $company = $this->company();

        $booking->loadMissing('vehicle');

        if (!$booking->vehicle || $booking->vehicle->rental_company_id !== $company->id) {
            abort(403);
        }
    }
}