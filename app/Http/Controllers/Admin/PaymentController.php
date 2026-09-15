<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user']);
        
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $payments = $query->orderByDesc('created_at')->paginate(15);
        
        return view('admin.payments.index', compact('payments'));
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,paid,failed,refunded'
        ]);

        $data = ['status' => $validated['status']];
        
        if ($validated['status'] === 'paid' && !$payment->paid_at) {
            $data['paid_at'] = now();
        }
        
        $payment->update($data);
        
        if ($validated['status'] === 'paid' && $payment->booking) {
            $payment->booking->update(['status' => 'confirmed']);
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
