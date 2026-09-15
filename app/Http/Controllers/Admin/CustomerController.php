<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->orderByDesc('created_at')
            ->paginate(15);
            
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        abort_if($customer->role !== 'customer', 404);
        
        $customer->load(['bookings.schedule.train', 'bookings.payment']);
        return view('admin.customers.show', compact('customer'));
    }
}
