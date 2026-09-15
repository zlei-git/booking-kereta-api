<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\Promotion;
use App\Models\Seat;
use App\Models\TrainSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function seats(Request $request, TrainSchedule $schedule)
    {
        $schedule->load(['train.classes', 'originStation', 'destinationStation']);
        $trainClass = $schedule->train->classes()->where('class_type', $schedule->class_type)->first();

        if (!$trainClass) {
            return back()->with('error', 'Kelas tidak tersedia untuk kereta ini.');
        }

        $seats = $trainClass->seats()->orderBy('seat_row')->orderBy('seat_column')->get();
        $bookedSeatIds = $schedule->getBookedSeatIds();
        $passengers = $request->get('passengers', session('booking_passengers_count', 1));
        session(['booking_passengers_count' => $passengers]);

        return view('booking.seats', compact('schedule', 'seats', 'bookedSeatIds', 'trainClass', 'passengers'));
    }

    public function storeSeats(Request $request, TrainSchedule $schedule)
    {
        $request->validate([
            'seats' => 'required|array|min:1',
            'seats.*' => 'exists:seats,id',
            'passengers' => 'required|integer|min:1|max:5',
        ]);

        $selectedSeats = $request->seats;
        $passengerCount = $request->passengers;

        if (count($selectedSeats) !== (int)$passengerCount) {
            return back()->with('error', "Pilih tepat {$passengerCount} kursi.");
        }

        // Check seat availability
        $bookedSeatIds = $schedule->getBookedSeatIds();
        foreach ($selectedSeats as $seatId) {
            if (in_array($seatId, $bookedSeatIds)) {
                return back()->with('error', 'Salah satu kursi sudah dipesan. Silakan pilih kursi lain.');
            }
        }

        session([
            'booking_schedule_id' => $schedule->id,
            'booking_selected_seats' => $selectedSeats,
            'booking_passengers_count' => $passengerCount,
        ]);

        return redirect()->route('booking.passengers', $schedule);
    }

    public function passengers(TrainSchedule $schedule)
    {
        $selectedSeats = session('booking_selected_seats', []);
        if (empty($selectedSeats)) {
            return redirect()->route('booking.seats', $schedule)->with('error', 'Pilih kursi terlebih dahulu.');
        }

        $schedule->load(['train', 'originStation', 'destinationStation']);
        $seats = Seat::whereIn('id', $selectedSeats)->orderBy('seat_row')->get();
        $passengerCount = count($selectedSeats);

        return view('booking.passengers', compact('schedule', 'seats', 'passengerCount'));
    }

    public function storePassengers(Request $request, TrainSchedule $schedule)
    {
        $passengerCount = count(session('booking_selected_seats', []));

        $rules = [];
        for ($i = 0; $i < $passengerCount; $i++) {
            $rules["passengers.{$i}.name"] = 'required|string|max:100';
            $rules["passengers.{$i}.identity_number"] = 'required|string|min:10|max:20';
            $rules["passengers.{$i}.phone"] = 'required|string|min:10|max:15';
            $rules["passengers.{$i}.email"] = 'required|email';
            $rules["passengers.{$i}.passenger_type"] = 'required|in:dewasa,anak,bayi';
        }

        $request->validate($rules, [
            'passengers.*.name.required' => 'Nama penumpang wajib diisi.',
            'passengers.*.identity_number.required' => 'Nomor identitas wajib diisi.',
            'passengers.*.identity_number.min' => 'Nomor identitas minimal 10 karakter.',
            'passengers.*.phone.required' => 'Nomor telepon wajib diisi.',
            'passengers.*.email.required' => 'Email wajib diisi.',
            'passengers.*.email.email' => 'Format email tidak valid.',
        ]);

        // Double-check seat availability
        $selectedSeats = session('booking_selected_seats', []);
        $bookedSeatIds = $schedule->getBookedSeatIds();

        foreach ($selectedSeats as $seatId) {
            if (in_array((int)$seatId, $bookedSeatIds)) {
                return redirect()->route('booking.seats', $schedule)
                    ->with('error', 'Kursi sudah tidak tersedia. Silakan pilih ulang.');
            }
        }

        // Create booking in a transaction
        $booking = DB::transaction(function () use ($request, $schedule, $selectedSeats) {
            $passengerCount = count($selectedSeats);
            $basePriceTotal = $schedule->base_price * $passengerCount;
            $serviceFee = 10000;
            $addonFee = $request->boolean('addon_meal') ? (45000 * $passengerCount) : 0;
            $subtotal = $basePriceTotal + $serviceFee + $addonFee;

            $discountAmount = 0;
            $appliedPromo = null;

            if ($request->filled('promo_code')) {
                $code = strtoupper(trim($request->promo_code));
                $promo = Promotion::where('code', $code)->first();
                if ($promo && $promo->isValidForAmount($subtotal)) {
                    $discountAmount = $promo->calculateDiscount($subtotal);
                    $appliedPromo = $promo->code;
                    $promo->increment('used_count');
                }
            }

            $totalPrice = max(0, $subtotal - $discountAmount);

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'schedule_id' => $schedule->id,
                'booking_number' => Booking::generateBookingNumber(),
                'passenger_count' => $passengerCount,
                'base_price' => $basePriceTotal,
                'service_fee' => $serviceFee,
                'addon_fee' => $addonFee,
                'discount_amount' => $discountAmount,
                'promo_code' => $appliedPromo,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'expired_at' => now()->addHours(2),
            ]);

            foreach ($request->passengers as $i => $passengerData) {
                BookingPassenger::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $selectedSeats[$i],
                    'name' => $passengerData['name'],
                    'id_type' => $passengerData['id_type'] ?? 'KTP',
                    'identity_number' => $passengerData['identity_number'],
                    'date_of_birth' => $passengerData['date_of_birth'] ?? null,
                    'phone' => $passengerData['phone'],
                    'email' => $passengerData['email'],
                    'passenger_type' => strtolower($passengerData['passenger_type'] ?? 'dewasa'),
                ]);
            }

            // Create pending payment
            $booking->payment()->create([
                'amount' => $totalPrice,
                'status' => 'pending',
            ]);

            return $booking;
        });

        // Clear session
        session()->forget(['booking_schedule_id', 'booking_selected_seats', 'booking_passengers_count']);

        return redirect()->route('booking.summary', $booking);
    }

    public function summary(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->load(['schedule.train', 'schedule.originStation', 'schedule.destinationStation', 'passengers.seat', 'payment']);

        return view('booking.summary', compact('booking'));
    }

    public function confirmation(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->load(['schedule.train', 'schedule.originStation', 'schedule.destinationStation', 'passengers.seat', 'payment']);

        return view('booking.confirmation', compact('booking'));
    }

    private function authorizeBooking(Booking $booking): void
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
