<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TrainSchedule;
use App\Models\Promotion;
use App\Models\Seat;
use App\Models\Booking;

class NordicRailFeatureTest extends TestCase
{
    public function test_promotions_page_loads_and_displays_codes()
    {
        $response = $this->get('/promotions');
        $response->assertStatus(200);
        $response->assertSee('Promosi Perjalanan');
        $response->assertSee('WEEKEND15');
        $response->assertSee('NORDIC10');
    }

    public function test_promo_validation_api_endpoint()
    {
        // Valid promo
        $response = $this->postJson('/api/promotions/validate', [
            'code' => 'WEEKEND15',
            'amount' => 200000,
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'code' => 'WEEKEND15',
        ]);

        // Invalid promo code
        $responseInvalid = $this->postJson('/api/promotions/validate', [
            'code' => 'KODEPALSU',
            'amount' => 200000,
        ]);
        $responseInvalid->assertStatus(404);

        // Spend below minimum
        $responseBelowMin = $this->postJson('/api/promotions/validate', [
            'code' => 'WEEKEND15',
            'amount' => 50000,
        ]);
        $responseBelowMin->assertStatus(422);
    }

    public function test_booking_with_meal_addon_and_promo_discount()
    {
        $user = User::where('role', 'customer')->first();
        $schedule = TrainSchedule::first();
        $trainClass = $schedule->train->classes()->where('class_type', $schedule->class_type)->first();
        $bookedSeatIds = $schedule->getBookedSeatIds();
        $seat = $trainClass->seats()->whereNotIn('id', $bookedSeatIds)->first();

        $response = $this->actingAs($user)
            ->withSession([
                'booking_selected_seats' => [$seat->id],
                'booking_passengers_count' => 1,
            ])
            ->post("/booking/{$schedule->id}/passengers", [
                'addon_meal' => 1,
                'promo_code' => 'NORDIC10',
                'passengers' => [
                    [
                        'name' => 'Siti Rahma',
                        'id_type' => 'KTP',
                        'identity_number' => '3171234567890002',
                        'phone' => '081298765432',
                        'email' => 'siti@example.com',
                        'passenger_type' => 'dewasa',
                    ],
                ],
            ]);

        $booking = Booking::where('user_id', $user->id)->orderByDesc('id')->first();
        $this->assertNotNull($booking);
        $this->assertEquals(45000, $booking->addon_fee);
        $this->assertEquals('NORDIC10', $booking->promo_code);
        $this->assertGreaterThan(0, $booking->discount_amount);
        $this->assertStringStartsWith('NR-2026-', $booking->booking_number);
    }

    public function test_card_payment_simulation()
    {
        $user = User::where('role', 'customer')->first();
        $booking = Booking::where('user_id', $user->id)->where('status', 'pending')->first();
        if (!$booking) {
            $schedule = TrainSchedule::first();
            $booking = Booking::create([
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'booking_number' => Booking::generateBookingNumber(),
                'passenger_count' => 1,
                'base_price' => $schedule->base_price,
                'service_fee' => 10000,
                'total_price' => $schedule->base_price + 10000,
                'status' => 'pending',
                'expired_at' => now()->addHours(2),
            ]);
            $booking->payment()->create([
                'amount' => $booking->total_price,
                'status' => 'pending',
            ]);
        }

        // Process with card
        $response = $this->actingAs($user)->post("/booking/{$booking->id}/payment", [
            'payment_method' => 'card',
        ]);
        $response->assertStatus(200);
        $response->assertSee('Data Kartu Kredit / Debit');

        // Confirm payment
        $payResponse = $this->actingAs($user)->post("/booking/{$booking->id}/pay");
        $payResponse->assertRedirect("/booking/{$booking->id}/confirmation");

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);
        $this->assertEquals('paid', $booking->payment->status);
    }
}
