<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class AvailabilityService
{
    public function check($vehicle, $startDate, $endDate): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        if (!$vehicle) {
            return [
                'available' => false,
                'message' => 'Vehicle not found.',
            ];
        }

        if (!$vehicle->available) {
            return [
                'available' => false,
                'message' => 'This vehicle is currently unavailable.',
            ];
        }

        if ($end->lessThanOrEqualTo($start)) {
            return [
                'available' => false,
                'message' => 'Return date must be after pickup date.',
            ];
        }

        $days = $start->diffInDays($end);

        if ($vehicle->minimum_days && $days < $vehicle->minimum_days) {
            return [
                'available' => false,
                'message' => 'Minimum rental period is ' . $vehicle->minimum_days . ' day(s).',
            ];
        }

        if ($vehicle->maximum_days && $days > $vehicle->maximum_days) {
            return [
                'available' => false,
                'message' => 'Maximum rental period is ' . $vehicle->maximum_days . ' day(s).',
            ];
        }

        $bookingConflict = Booking::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['pending', 'confirmed', 'approved'])
            ->where(function ($query) use ($start, $end) {
                $query->whereDate('start_date', '<', $end)
                    ->whereDate('end_date', '>', $start);
            })
            ->exists();

        if ($bookingConflict) {
            return [
                'available' => false,
                'message' => 'This vehicle is already booked for the selected dates.',
            ];
        }

        $blockedConflict = $vehicle->availabilityBlocks()
            ->where(function ($query) use ($start, $end) {
                $query->whereDate('blocked_from', '<', $end)
                    ->whereDate('blocked_until', '>', $start);
            })
            ->first();

        if ($blockedConflict) {
            $reason = $blockedConflict->reason ?: ucfirst(str_replace('_', ' ', $blockedConflict->type));

            return [
                'available' => false,
                'message' => 'This vehicle is blocked for the selected dates. Reason: ' . $reason . '.',
            ];
        }

        return [
            'available' => true,
            'message' => 'Vehicle is available.',
            'days' => $days,
        ];
    }
}