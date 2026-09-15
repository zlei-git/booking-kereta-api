<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('train_schedules')->cascadeOnDelete();
            $table->string('booking_number', 30)->unique();
            $table->integer('passenger_count');
            $table->integer('base_price');
            $table->integer('service_fee')->default(10000);
            $table->integer('addon_fee')->default(0);
            $table->integer('discount_amount')->default(0);
            $table->string('promo_code', 50)->nullable();
            $table->integer('total_price');
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'boarding', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
