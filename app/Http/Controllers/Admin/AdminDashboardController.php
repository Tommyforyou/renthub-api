<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\RentalCompany;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();

        $confirmedBookings = Booking::where('status', 'confirmed')
            ->count();

        $totalCommission = Booking::where('status', 'confirmed')
            ->sum('commission_amount');

        $totalRevenue = Booking::where('status', 'confirmed')
            ->sum('total_amount');

        $totalCustomers = User::count();

        $totalVehicles = Vehicle::count();

        $totalCompanies = RentalCompany::count();

        return view('admin.dashboard', compact(
            'totalBookings',
            'confirmedBookings',
            'totalCommission',
            'totalRevenue',
            'totalCustomers',
            'totalVehicles',
            'totalCompanies'
        ));
    }
}
