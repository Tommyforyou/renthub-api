<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RentalCompany;
use App\Models\User;
use App\Models\Vehicle;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $totalCompanies = RentalCompany::count();

        $pendingCompanies = RentalCompany::where('status', 'pending')->count();

        $approvedCompanies = RentalCompany::where('status', 'approved')->count();

        $totalVehicles = Vehicle::count();

        $availableVehicles = Vehicle::where('available', true)->count();

        $totalBookings = Booking::count();

        $pendingBookings = Booking::where('status', 'pending')->count();

        $approvedBookings = Booking::whereIn('status', [
            'approved',
            'confirmed'
        ])->count();

        $completedBookings = Booking::where('status', 'completed')->count();

        $totalRevenue = Booking::sum('total_amount');

        $totalCommission = Booking::sum('commission_amount');

        $recentBookings = Booking::with([
                'vehicle',
                'customer'
            ])
            ->latest()
            ->take(8)
            ->get();

        $recentCompanies = RentalCompany::latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCompanies',
            'pendingCompanies',
            'approvedCompanies',
            'totalVehicles',
            'availableVehicles',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'completedBookings',
            'totalRevenue',
            'totalCommission',
            'recentBookings',
            'recentCompanies'
        ));
    }
}