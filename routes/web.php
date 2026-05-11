<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalCompanyController;
use App\Http\Controllers\Admin\CompanyApprovalController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CompanyBookingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\CompanyAvailabilityController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cars',
    [VehicleController::class, 'index'])
    ->name('cars.index');

Route::get('/cars/{vehicle}',
    [VehicleController::class, 'show'])
    ->name('cars.show');

Route::get('/companies/{company}',
    [RentalCompanyController::class, 'show'])
    ->name('companies.show');

Route::get('/vehicles/{vehicle}/unavailable-dates',
    [PricingController::class, 'unavailableDates'])
    ->name('vehicles.unavailable-dates');

Route::post('/vehicles/{vehicle}/calculate-price',
    [PricingController::class, 'calculate'])
    ->name('vehicles.calculate-price');

/*
|--------------------------------------------------------------------------
| Authenticated Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    if ($role === 'admin') {
        return redirect('/admin/dashboard');
    }

    if ($role === 'rental_company') {
        return redirect('/company/dashboard');
    }

    return redirect('/cars');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications',
        [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/read-all',
        [NotificationController::class, 'readAll'])
        ->name('notifications.readAll');

    Route::post('/notifications/{notification}/read',
        [NotificationController::class, 'read'])
        ->name('notifications.read');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile',
        [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile',
        [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile',
        [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Rental Company Registration
    |--------------------------------------------------------------------------
    */

    Route::get('/company/register',
        [RentalCompanyController::class, 'create'])
        ->name('company.create');

    Route::post('/company/register',
        [RentalCompanyController::class, 'store'])
        ->name('company.store');

    /*
    |--------------------------------------------------------------------------
    | Customer Bookings
    |--------------------------------------------------------------------------
    */

    Route::get('/my-bookings',
        [BookingController::class, 'myBookings'])
        ->name('bookings.my');

    Route::post('/my-bookings/{booking}/cancel',
        [BookingController::class, 'cancel'])
        ->name('bookings.cancel');

    Route::get('/cars/{vehicle}/book',
        [BookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/cars/{vehicle}/book',
        [BookingController::class, 'store'])
        ->name('bookings.store');

    /*
    |--------------------------------------------------------------------------
    | Invoices
    |--------------------------------------------------------------------------
    */

    Route::get('/bookings/{booking}/invoice',
        [BookingController::class, 'invoice'])
        ->name('bookings.invoice');

    Route::get('/bookings/{booking}/invoice/download',
        [BookingController::class, 'downloadInvoice'])
        ->name('bookings.invoice.download');

    /*
    |--------------------------------------------------------------------------
    | Favorites
    |--------------------------------------------------------------------------
    */

    Route::get('/favorites',
        [FavoriteController::class, 'index'])
        ->name('favorites.index');

    Route::post('/favorites/{vehicle}',
        [FavoriteController::class, 'store'])
        ->name('favorites.store');

    Route::delete('/favorites/{vehicle}',
        [FavoriteController::class, 'destroy'])
        ->name('favorites.destroy');

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    Route::get('/payments',
        [PaymentController::class, 'index'])
        ->name('payments.index');

    Route::post('/bookings/{booking}/payments',
        [PaymentController::class, 'store'])
        ->name('payments.store');


});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard',
        [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/companies',
        [CompanyApprovalController::class, 'index'])
        ->name('admin.companies');

    Route::post('/admin/companies/{id}/approve',
        [CompanyApprovalController::class, 'approve'])
        ->name('admin.companies.approve');

    Route::post('/admin/companies/{id}/reject',
        [CompanyApprovalController::class, 'reject'])
        ->name('admin.companies.reject');

});

/*
|--------------------------------------------------------------------------
| Rental Company Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:rental_company'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Company Dashboard, Bookings and Calendar
    |--------------------------------------------------------------------------
    */

    Route::get('/company/dashboard',
        [CompanyBookingController::class, 'dashboard'])
        ->name('company.dashboard');

    Route::get('/company/bookings',
        [CompanyBookingController::class, 'index'])
        ->name('company.bookings');

    Route::get('/company/calendar',
        [CompanyBookingController::class, 'calendar'])
        ->name('company.calendar');

    /*
    |--------------------------------------------------------------------------
    | Company Vehicle Management
    |--------------------------------------------------------------------------
    */

    Route::get('/company/vehicles',
        [VehicleController::class, 'myVehicles'])
        ->name('company.vehicles');

    Route::get('/vehicles/create',
        [VehicleController::class, 'create'])
        ->name('vehicles.create');

    Route::post('/vehicles',
        [VehicleController::class, 'store'])
        ->name('vehicles.store');

    Route::get('/vehicles/{vehicle}/edit',
        [VehicleController::class, 'edit'])
        ->name('vehicles.edit');

    Route::get('/vehicles/{vehicle}',
        [VehicleController::class, 'show'])
        ->name('vehicles.show');

    Route::put('/vehicles/{vehicle}',
        [VehicleController::class, 'update'])
        ->name('vehicles.update');

    Route::delete('/vehicles/{vehicle}',
        [VehicleController::class, 'destroy'])
        ->name('vehicles.destroy');

    /*
    |--------------------------------------------------------------------------
    | Vehicle Images
    |--------------------------------------------------------------------------
    */

    Route::delete('/vehicles/images/{image}',
        [VehicleController::class, 'deleteImage'])
        ->name('vehicles.images.delete');

    Route::post('/vehicles/images/{image}/primary',
        [VehicleController::class, 'setPrimaryImage'])
        ->name('vehicles.images.primary');

    /*
    |--------------------------------------------------------------------------
    | Vehicle Availability
    |--------------------------------------------------------------------------
    */

    Route::get('/company/vehicles/{vehicle}/availability',
        [CompanyAvailabilityController::class, 'index'])
        ->name('company.vehicles.availability');

    Route::post('/company/vehicles/{vehicle}/availability',
        [CompanyAvailabilityController::class, 'store'])
        ->name('company.vehicles.availability.store');

    Route::delete('/company/availability/{availability}',
        [CompanyAvailabilityController::class, 'destroy'])
        ->name('company.vehicles.availability.destroy');

    /*
    |--------------------------------------------------------------------------
    | Company Booking Actions
    |--------------------------------------------------------------------------
    */

    Route::post('/company/bookings/{booking}/approve',
        [CompanyBookingController::class, 'approve'])
        ->name('company.bookings.approve');

    Route::post('/company/bookings/{booking}/reject',
        [CompanyBookingController::class, 'reject'])
        ->name('company.bookings.reject');

    Route::post('/company/bookings/{booking}/complete',
        [CompanyBookingController::class, 'complete'])
        ->name('company.bookings.complete');

    Route::post('/company/bookings/{booking}/mark-paid',
        [CompanyBookingController::class, 'markPaid'])
        ->name('company.bookings.markPaid');


    /*
    |--------------------------------------------------------------------------
    | Company Payment Actions
    |--------------------------------------------------------------------------
    */

    Route::post('/company/bookings/{booking}/payment/mark-paid',
        [PaymentController::class, 'markPaid'])
        ->name('company.payments.markPaid');        

});

/*
|--------------------------------------------------------------------------
| Emergency Logout Route
|--------------------------------------------------------------------------
*/

Route::get('/force-logout', function () {
    auth()->logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Auth Scaffolding Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';