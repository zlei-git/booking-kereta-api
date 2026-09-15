<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TrainSchedule;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\Payment;
use App\Models\Seat;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have some sample customers
        $customers = [
            [
                'name' => 'Siti Rahmawati',
                'email' => 'siti@demo.id',
                'phone' => '081234567891',
                'password' => 'password',
                'role' => 'customer',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@demo.id',
                'phone' => '081234567892',
                'password' => 'password',
                'role' => 'customer',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@demo.id',
                'phone' => '081234567893',
                'password' => 'password',
                'role' => 'customer',
            ],
            [
                'name' => 'Rizky Pratama',
                'email' => 'rizky@demo.id',
                'phone' => '081234567894',
                'password' => 'password',
                'role' => 'customer',
            ],
        ];

        $users = [User::where('email', 'budi@demo.id')->first()];
        foreach ($customers as $c) {
            $user = User::firstOrCreate(['email' => $c['email']], $c);
            $users[] = $user;
        }
        $users = array_filter($users);

        $schedules = TrainSchedule::with('train.classes.seats')->take(10)->get();
        if ($schedules->isEmpty()) {
            return;
        }

        $sampleBookings = [
            // Today bookings
            ['status' => 'confirmed', 'payment_status' => 'paid', 'hours_ago' => 1, 'passengers' => 2, 'method' => 'qris'],
            ['status' => 'confirmed', 'payment_status' => 'paid', 'hours_ago' => 3, 'passengers' => 1, 'method' => 'virtual_account'],
            ['status' => 'pending',   'payment_status' => 'pending', 'hours_ago' => 2, 'passengers' => 2, 'method' => 'bank_transfer'],
            ['status' => 'confirmed', 'payment_status' => 'paid', 'hours_ago' => 5, 'passengers' => 1, 'method' => 'ewallet'],
            
            // Recent days bookings
            ['status' => 'completed', 'payment_status' => 'paid', 'hours_ago' => 24, 'passengers' => 2, 'method' => 'qris'],
            ['status' => 'completed', 'payment_status' => 'paid', 'hours_ago' => 30, 'passengers' => 1, 'method' => 'virtual_account'],
            ['status' => 'confirmed', 'payment_status' => 'paid', 'hours_ago' => 48, 'passengers' => 3, 'method' => 'bank_transfer'],
            ['status' => 'pending',   'payment_status' => 'pending', 'hours_ago' => 6,  'passengers' => 1, 'method' => 'qris'],
            ['status' => 'confirmed', 'payment_status' => 'paid', 'hours_ago' => 72, 'passengers' => 2, 'method' => 'virtual_account'],
            ['status' => 'completed', 'payment_status' => 'paid', 'hours_ago' => 96, 'passengers' => 1, 'method' => 'ewallet'],
        ];

        $names = [
            'Budi Santoso', 'Siti Rahmawati', 'Ahmad Fauzi', 'Dewi Lestari',
            'Rizky Pratama', 'Maya Anggraini', 'Dian Sastro', 'Eko Prasetyo',
            'Indah Permata', 'Farhan Maulana', 'Anisa Triana', 'Bambang Pamungkas'
        ];

        $seq = 101;
        foreach ($sampleBookings as $idx => $bData) {
            $schedule = $schedules[$idx % $schedules->count()];
            $user = $users[$idx % count($users)];
            $createdAt = Carbon::now()->subHours($bData['hours_ago']);

            $pCount = $bData['passengers'];
            $basePrice = (int) $schedule->base_price * $pCount;
            $serviceFee = 7500;
            $totalPrice = $basePrice + $serviceFee;

            $bookingNumber = 'NR-' . $createdAt->format('Y') . '-' . str_pad($seq++, 6, '0', STR_PAD_LEFT);

            $booking = Booking::create([
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'booking_number' => $bookingNumber,
                'passenger_count' => $pCount,
                'base_price' => $basePrice,
                'service_fee' => $serviceFee,
                'addon_fee' => 0,
                'discount_amount' => 0,
                'total_price' => $totalPrice,
                'status' => $bData['status'],
                'expired_at' => $createdAt->copy()->addHours(2),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Create Passengers
            $seats = Seat::whereHas('trainClass', function ($q) use ($schedule) {
                $q->where('train_id', $schedule->train_id);
            })->take(50)->get();

            for ($p = 0; $p < $pCount; $p++) {
                $seat = $seats->isNotEmpty() ? $seats->random() : null;
                $pName = $names[($idx * 2 + $p) % count($names)];
                BookingPassenger::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seat ? $seat->id : null,
                    'name' => $pName,
                    'id_type' => 'KTP',
                    'identity_number' => '3578' . rand(100000000000, 999999999999),
                    'date_of_birth' => Carbon::now()->subYears(rand(20, 45))->format('Y-m-d'),
                    'phone' => '0812' . rand(10000000, 99999999),
                    'email' => Str::slug($pName) . '@mail.com',
                    'passenger_type' => 'dewasa',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Create Payment
            Payment::create([
                'booking_id' => $booking->id,
                'method' => $bData['method'],
                'amount' => $totalPrice,
                'status' => $bData['payment_status'],
                'va_number' => '8801' . rand(1000000000, 9999999999),
                'reference_number' => 'PAY-' . strtoupper(Str::random(10)),
                'paid_at' => $bData['payment_status'] === 'paid' ? $createdAt->copy()->addMinutes(12) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
