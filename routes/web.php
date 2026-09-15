<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AdminLoginController;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StationController as AdminStationController;
use App\Http\Controllers\Admin\TrainController as AdminTrainController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;

// Health check route for Render & monitors
Route::get('/healthz', fn() => response('OK', 200));

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules');
Route::get('/jadwal', [ScheduleController::class, 'index'])->name('schedules.index');
Route::get('/layanan', [HomeController::class, 'services'])->name('services');
Route::get('/panduan', [HomeController::class, 'guide'])->name('guide');
Route::get('/promotions', [HomeController::class, 'promotions'])->name('promotions');
Route::post('/api/promotions/validate', [HomeController::class, 'validatePromotion'])->name('promotions.validate');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout')->middleware('auth');

// Customer routes (authenticated)
Route::middleware('auth')->group(function () {
    // Booking flow
    Route::get('/booking/{schedule}/seats', [BookingController::class, 'seats'])->name('booking.seats');
    Route::post('/booking/{schedule}/seats', [BookingController::class, 'storeSeats'])->name('booking.storeSeats');
    Route::get('/booking/{schedule}/passengers', [BookingController::class, 'passengers'])->name('booking.passengers');
    Route::post('/booking/{schedule}/passengers', [BookingController::class, 'storePassengers'])->name('booking.storePassengers');
    Route::get('/booking/{booking}/summary', [BookingController::class, 'summary'])->name('booking.summary');
    Route::get('/booking/{booking}/payment', [PaymentController::class, 'show'])->name('booking.payment');
    Route::post('/booking/{booking}/payment', [PaymentController::class, 'process'])->name('booking.processPayment');
    Route::post('/booking/{booking}/pay', [PaymentController::class, 'confirmPayment'])->name('booking.confirmPayment');
    Route::get('/booking/{booking}/confirmation', [BookingController::class, 'confirmation'])->name('booking.confirmation');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{booking}/ticket', [OrderController::class, 'ticket'])->name('orders.ticket');
    Route::post('/orders/{booking}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('stations', AdminStationController::class);
    Route::resource('trains', AdminTrainController::class);
    Route::resource('schedules', AdminScheduleController::class);
    
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}/status', [AdminPaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}', [AdminCustomerController::class, 'show'])->name('customers.show');
    
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{contact}/read', [AdminContactController::class, 'markAsRead'])->name('contacts.markAsRead');
});
