<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleAvailability;
use Illuminate\Http\Request;

class CompanyAvailabilityController extends Controller
{
    public function index(Vehicle $vehicle)
    {
        $blocks = $vehicle->availabilityBlocks()
            ->latest()
            ->get();

        return view(
            'company.vehicles.availability',
            compact('vehicle', 'blocks')
        );
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'blocked_from' => ['required', 'date'],
            'blocked_until' => ['required', 'date', 'after_or_equal:blocked_from'],
            'type' => ['required'],
            'reason' => ['nullable', 'string'],
        ]);

        VehicleAvailability::create([
            'vehicle_id' => $vehicle->id,
            'blocked_from' => $request->blocked_from,
            'blocked_until' => $request->blocked_until,
            'type' => $request->type,
            'reason' => $request->reason,
        ]);

        return back()->with(
            'success',
            'Availability block created successfully.'
        );
    }

    public function destroy(VehicleAvailability $availability)
    {
        $availability->delete();

        return back()->with(
            'success',
            'Availability block removed successfully.'
        );
    }
}