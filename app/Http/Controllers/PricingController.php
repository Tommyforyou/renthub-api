<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function calculate(
        Request $request,
        Vehicle $vehicle,
        PricingService $pricingService,
        AvailabilityService $availabilityService
    ) {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        $availability = $availabilityService->check(
            $vehicle,
            $request->start_date,
            $request->end_date
        );

        if (!$availability['available']) {
            return response()->json([
                'success' => false,
                'message' => $availability['message'],
            ], 422);
        }

        $pricing = $pricingService->calculate(
            $vehicle,
            $request->start_date,
            $request->end_date
        );

        return response()->json([
            'success' => true,
            'available' => true,
            'pricing' => $pricing,
        ]);
    }

    public function unavailableDates(Vehicle $vehicle)
    {
        $dates = [];

        $bookings = \App\Models\Booking::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['pending', 'confirmed', 'approved'])
            ->get();

        foreach ($bookings as $booking) {
            $period = \Carbon\CarbonPeriod::create(
                $booking->start_date,
                $booking->end_date
            );

            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        $blocks = \App\Models\VehicleAvailability::where('vehicle_id', $vehicle->id)->get();

        foreach ($blocks as $block) {
            $period = \Carbon\CarbonPeriod::create(
                $block->blocked_from,
                $block->blocked_until
            );

            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        return response()->json(array_values(array_unique($dates)));
    }
}
