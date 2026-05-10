<?php

namespace App\Services;

use Carbon\Carbon;

class PricingService
{
    public function calculate($vehicle, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $days = $start->diffInDays($end);

        if ($days <= 0) {
            return null;
        }

        $dailyPrice = $vehicle->daily_price ?: $vehicle->price_per_day;

        $subtotal = 0;

        for ($date = $start->copy(); $date->lt($end); $date->addDay()) {

            $dayPrice = $dailyPrice;

            /*
            |--------------------------------------------------------------------------
            | Weekend Pricing
            |--------------------------------------------------------------------------
            */

            if ($date->isWeekend()) {
                $dayPrice *= $vehicle->weekend_multiplier;
            }

            /*
            |--------------------------------------------------------------------------
            | Seasonal Pricing
            |--------------------------------------------------------------------------
            */

            $season = $vehicle->seasonalPrices()
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->first();

            if ($season) {
                $dayPrice *= $season->price_multiplier;
            }

            $subtotal += $dayPrice;
        }

        /*
        |--------------------------------------------------------------------------
        | Discounts
        |--------------------------------------------------------------------------
        */

        $discount = 0;

        if ($days >= 30 && $vehicle->monthly_discount > 0) {
            $discount = ($subtotal * $vehicle->monthly_discount) / 100;
        } elseif ($days >= 7 && $vehicle->weekly_discount > 0) {
            $discount = ($subtotal * $vehicle->weekly_discount) / 100;
        }

        $subtotalAfterDiscount = $subtotal - $discount;

        /*
        |--------------------------------------------------------------------------
        | Fees
        |--------------------------------------------------------------------------
        */

        $deliveryFee = $vehicle->delivery_fee ?? 0;
        $deposit = $vehicle->security_deposit ?? 0;

        /*
        |--------------------------------------------------------------------------
        | VAT
        |--------------------------------------------------------------------------
        */

        $vat = $subtotalAfterDiscount * 0.15;

        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $total = $subtotalAfterDiscount
            + $vat
            + $deliveryFee
            + $deposit;

        return [
            'days' => $days,

            'base_subtotal' => round($subtotal, 2),

            'discount' => round($discount, 2),

            'subtotal' => round($subtotalAfterDiscount, 2),

            'vat' => round($vat, 2),

            'delivery_fee' => round($deliveryFee, 2),

            'deposit' => round($deposit, 2),

            'grand_total' => round($total, 2),
        ];
    }
}