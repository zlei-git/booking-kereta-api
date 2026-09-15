<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['schedule.train', 'schedule.originStation', 'schedule.destinationStation', 'payment'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('orders.index', compact('bookings'));
    }

    public function ticket(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['schedule.train', 'schedule.originStation', 'schedule.destinationStation', 'passengers.seat', 'payment']);

        return view('orders.ticket', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['pending'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        if ($booking->payment) {
            $booking->payment->status = 'failed';
            $booking->payment->save();
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
