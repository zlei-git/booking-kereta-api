<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'schedule.train']);
        
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $bookings = $query->orderByDesc('created_at')->paginate(15);
        
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'schedule.train', 'schedule.originStation', 'schedule.destinationStation', 'passengers', 'payment']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->update(['status' => $validated['status']]);
        
        if ($validated['status'] === 'cancelled' && $booking->payment) {
            $booking->payment->update(['status' => 'failed']);
        }

        return back()->with('success', 'Status pemesanan berhasil diperbarui.');
    }
}
