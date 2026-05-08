<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalCompanyController;
use App\Http\Controllers\Admin\CompanyApprovalController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CompanyBookingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/cars', [VehicleController::class, 'index'])->name('cars.index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/company/register', [RentalCompanyController::class, 'create'])->name('company.create');
    Route::post('/company/register', [RentalCompanyController::class, 'store'])->name('company.store');

    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::post('/my-bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/cars/{vehicle}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/cars/{vehicle}/book', [BookingController::class, 'store'])->name('bookings.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/companies', [CompanyApprovalController::class, 'index'])->name('admin.companies');
    Route::post('/admin/companies/{id}/approve', [CompanyApprovalController::class, 'approve'])->name('admin.companies.approve');
    Route::post('/admin/companies/{id}/reject', [CompanyApprovalController::class, 'reject'])->name('admin.companies.reject');
});

Route::middleware(['auth', 'role:rental_company'])->group(function () {
    Route::get('/company/dashboard', [CompanyBookingController::class, 'dashboard'])->name('company.dashboard');
    Route::get('/company/bookings', [CompanyBookingController::class, 'index'])->name('company.bookings');
    Route::post('/company/bookings/{booking}/approve', [CompanyBookingController::class, 'approve'])->name('company.bookings.approve');
    Route::post('/company/bookings/{booking}/reject', [CompanyBookingController::class, 'reject'])->name('company.bookings.reject');

    Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
});

Route::get('/company/vehicles', [VehicleController::class, 'myVehicles'])
    ->name('company.vehicles');



require __DIR__.'/auth.php';