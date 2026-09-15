<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('train_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_id')->constrained()->cascadeOnDelete();
            $table->foreignId('origin_station_id')->constrained('stations')->cascadeOnDelete();
            $table->foreignId('destination_station_id')->constrained('stations')->cascadeOnDelete();
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->date('travel_date');
            $table->enum('class_type', ['ekonomi', 'bisnis', 'eksekutif']);
            $table->integer('base_price');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['origin_station_id', 'destination_station_id', 'travel_date'], 'schedule_search_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('train_schedules');
    }
};
