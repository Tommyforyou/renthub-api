<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'completed') {
            return back()->withErrors([
                'review' => 'You can only review completed bookings.'
            ]);
        }

        return view('reviews.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'completed') {
            return back()->withErrors([
                'review' => 'You can only review completed bookings.'
            ]);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            [
                'booking_id' => $booking->id,
                'customer_id' => Auth::id(),
            ],
            [
                'vehicle_id' => $booking->vehicle_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return redirect('/my-bookings')
            ->with('success', 'Review submitted successfully.');
    }
}
