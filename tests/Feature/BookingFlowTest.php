<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TrainSchedule;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingFlowTest extends TestCase
{
    public function test_homepage_loads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('NordicRail');
        $response->assertSee('Travel, Refined.');
    }

    public function test_dedicated_pages_load()
    {
        // Test schedules page (both /schedules and /jadwal)
        $this->get('/schedules')->assertStatus(200)->assertSee('Jadwal & Tarif Kereta Api');
        $this->get('/jadwal')->assertStatus(200)->assertSee('Jadwal & Tarif Kereta Api');

        // Test services page
        $this->get('/layanan')->assertStatus(200)->assertSee('Armada & Layanan');

        // Test guide page
        $this->get('/panduan')->assertStatus(200)->assertSee('Panduan Perjalanan');

        // Test contact page
        $this->get('/contact')->assertStatus(200)->assertSee('Hubungi Kami');

        // Test promotions page
        $this->get('/promotions')->assertStatus(200)->assertSee('Promosi Perjalanan');
    }

    public function test_search_results_loads()
    {
        $schedule = TrainSchedule::first();
        $response = $this->get('/search?origin=' . $schedule->origin_station_id . '&destination=' . $schedule->destination_station_id . '&date=' . $schedule->travel_date->format('Y-m-d') . '&passengers=1');
        $response->assertStatus(200);
        $response->assertSee($schedule->train->name);
    }

    public function test_full_booking_flow()
    {
        $user = User::where('role', 'customer')->first();
        $schedule = TrainSchedule::first();
        $trainClass = $schedule->train->classes()->where('class_type', $schedule->class_type)->first();
        $bookedSeatIds = $schedule->getBookedSeatIds();
        $seat = $trainClass->seats()->whereNotIn('id', $bookedSeatIds)->first();

        // 1. View seat selection
        $response = $this->actingAs($user)->get("/booking/{$schedule->id}/seats?passengers=1");
        $response->assertStatus(200);
        $response->assertSee('Pilih Kursi');

        // 2. Store seat selection
        $response = $this->actingAs($user)->post("/booking/{$schedule->id}/seats", [
            'seats' => [$seat->id],
            'passengers' => 1,
        ]);
        $response->assertRedirect("/booking/{$schedule->id}/passengers");

        // 3. View passenger form
        $response = $this->actingAs($user)
            ->withSession([
                'booking_selected_seats' => [$seat->id],
                'booking_passengers_count' => 1
            ])
            ->get("/booking/{$schedule->id}/passengers");
        $response->assertStatus(200);
        $response->assertSee('Data Penumpang');

        // 4. Store passenger information
        $response = $this->actingAs($user)
            ->withSession([
                'booking_selected_seats' => [$seat->id],
                'booking_passengers_count' => 1
            ])
            ->post("/booking/{$schedule->id}/passengers", [
                'passengers' => [
                    [
                        'name' => 'Budi Santoso',
                        'identity_number' => '3271234567890001',
                        'phone' => '081234567890',
                        'email' => 'budi@demo.id',
                        'passenger_type' => 'dewasa'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'status' => 'pending',
        ]);

        $booking = Booking::where('user_id', $user->id)->latest()->first();
        $response->assertRedirect("/booking/{$booking->id}/summary");

        // 5. Order summary
        $response = $this->actingAs($user)->get("/booking/{$booking->id}/summary");
        $response->assertStatus(200);
        $response->assertSee($booking->booking_number);

        // 6. Payment method page
        $response = $this->actingAs($user)->get("/booking/{$booking->id}/payment");
        $response->assertStatus(200);
        $response->assertSee('Pilih Metode Pembayaran');

        // 7. Process payment (QRIS)
        $response = $this->actingAs($user)->post("/booking/{$booking->id}/payment", [
            'payment_method' => 'qris',
        ]);
        $response->assertStatus(200);
        $response->assertSee('Scan QR Code untuk Pembayaran');

        // 8. Confirm payment
        $response = $this->actingAs($user)->post("/booking/{$booking->id}/pay");
        $response->assertRedirect("/booking/{$booking->id}/confirmation");

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);
        $this->assertEquals('paid', $booking->payment->status);

        // 9. View confirmation
        $response = $this->actingAs($user)->get("/booking/{$booking->id}/confirmation");
        $response->assertStatus(200);
        $response->assertSee('Pemesanan Berhasil Dikonfirmasi');

        // 10. View ticket
        $response = $this->actingAs($user)->get("/orders/{$booking->id}/ticket");
        $response->assertStatus(200);
        $response->assertSee('BOARDING PASS E-TIKET');

        // 11. View my orders
        $response = $this->actingAs($user)->get("/orders");
        $response->assertStatus(200);
        $response->assertSee($booking->booking_number);
    }

    public function test_admin_flow()
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Admin dashboard
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');

        // 2. Admin stations
        $response = $this->actingAs($admin)->get('/admin/stations');
        $response->assertStatus(200);
        $response->assertSee('Gambir');

        // 3. Admin trains
        $response = $this->actingAs($admin)->get('/admin/trains');
        $response->assertStatus(200);
        $response->assertSee('Argo Wilis');

        // 4. Admin schedules
        $response = $this->actingAs($admin)->get('/admin/schedules');
        $response->assertStatus(200);

        // 5. Admin bookings
        $response = $this->actingAs($admin)->get('/admin/bookings');
        $response->assertStatus(200);

        // 6. Admin payments
        $response = $this->actingAs($admin)->get('/admin/payments');
        $response->assertStatus(200);

        // 7. Admin customers
        $response = $this->actingAs($admin)->get('/admin/customers');
        $response->assertStatus(200);

        // 8. Admin contacts
        $response = $this->actingAs($admin)->get('/admin/contacts');
        $response->assertStatus(200);
    }
}
