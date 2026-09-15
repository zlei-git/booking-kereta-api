<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status === 'cancelled') {
            return redirect()->route('orders.index')->with('error', 'Pesanan telah dibatalkan.');
        }

        $booking->load(['schedule.train', 'schedule.originStation', 'schedule.destinationStation', 'passengers.seat', 'payment']);

        return view('booking.payment', compact('booking'));
    }

    public function process(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $request->validate([
            'payment_method' => 'required|in:bank_transfer,virtual_account,ewallet,qris,card',
        ]);

        $payment = $booking->payment;
        $payment->method = $request->payment_method;

        if ($request->payment_method === 'virtual_account') {
            $payment->va_number = '8800' . rand(1000000000, 9999999999);
        }

        $payment->reference_number = 'REF-' . strtoupper(substr(md5(uniqid()), 0, 12));
        $payment->save();

        $booking->load(['schedule.train', 'schedule.originStation', 'schedule.destinationStation', 'passengers.seat', 'payment']);

        return view('booking.payment-process', compact('booking'));
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $payment = $booking->payment;

        if ($payment->status === 'paid') {
            return redirect()->route('booking.confirmation', $booking);
        }

        $payment->status = 'paid';
        $payment->paid_at = now();
        $payment->save();

        $booking->status = 'confirmed';
        $booking->save();

        return redirect()->route('booking.confirmation', $booking);
    }

    private function authorizeBooking(Booking $booking): void
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
