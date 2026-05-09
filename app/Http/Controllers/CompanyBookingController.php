<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\RentalCompany;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusMail;

class CompanyBookingController extends Controller
{
    public function index()
    {
        $company = RentalCompany::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->firstOrFail();

        $bookings = Booking::with(['vehicle', 'customer'])
            ->whereHas('vehicle', function ($query) use ($company) {
                $query->where('rental_company_id', $company->id);
            })
            ->latest()
            ->get();

        return view('company.bookings.index', compact('bookings'));
    }

    public function approve(Booking $booking)
    {
        $this->authorizeCompanyBooking($booking);

        $booking->status = 'confirmed';
        $booking->save();

        Mail::to($booking->customer->email)
            ->send(new BookingStatusMail($booking, 'Your booking has been approved.'));
        
        return back()->with('success', 'Booking approved.');
    }

    public function reject(Booking $booking)
    {
        $this->authorizeCompanyBooking($booking);

        $booking->status = 'cancelled';
        $booking->save();

        Mail::to($booking->customer->email)
        ->send(new BookingStatusMail($booking, 'Your booking has been rejected.'));
        
        return back()->with('success', 'Booking rejected.');
    }

    public function complete(Booking $booking)
    {
        $this->authorizeCompanyBooking($booking);

        if ($booking->status !== 'confirmed') {
            return back()->withErrors([
                'booking' => 'Only confirmed bookings can be completed.'
            ]);
        }

        $booking->status = 'completed';
        $booking->save();

        Mail::to($booking->customer->email)
        ->send(new BookingStatusMail($booking, 'Your rental has been marked as completed.'));

        return back()->with('success', 'Booking marked as completed.');
    }


    private function authorizeCompanyBooking(Booking $booking): void
    {
        $company = RentalCompany::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->firstOrFail();

        if ($booking->vehicle->rental_company_id !== $company->id) {
            abort(403);
        }
    }

    public function dashboard()
    {
        $company = RentalCompany::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->firstOrFail();

        $vehicleIds = $company->vehicles()->pluck('id');

        $bookings = Booking::whereIn('vehicle_id', $vehicleIds);

        $totalRevenue = (clone $bookings)
            ->where('status', 'confirmed')
            ->sum('owner_payout_amount');

        $totalCommission = (clone $bookings)
            ->where('status', 'confirmed')
            ->sum('commission_amount');

        $pendingBookings = (clone $bookings)
            ->where('status', 'pending')
            ->count();

        $confirmedBookings = (clone $bookings)
            ->where('status', 'confirmed')
            ->count();

        $vehicleCount = $company->vehicles()->count();

        return view('company.dashboard', compact(
            'totalRevenue',
            'totalCommission',
            'pendingBookings',
            'confirmedBookings',
            'vehicleCount'
        ));
} 



}
