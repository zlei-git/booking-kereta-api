<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Train;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $paidRevenue = Payment::where('status', 'paid')->sum('amount');
        $bookingRevenue = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        $stats = [
            'total_bookings' => Booking::count(),
            'today_bookings' => Booking::whereDate('created_at', today())->count(),
            'revenue' => max((int)$paidRevenue, (int)$bookingRevenue),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'active_trains' => Train::where('is_active', true)->count(),
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        $recentBookings = Booking::with(['user', 'schedule.train', 'schedule.originStation', 'schedule.destinationStation', 'payment'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}
