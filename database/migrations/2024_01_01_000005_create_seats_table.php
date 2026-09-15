<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_class_id')->constrained()->cascadeOnDelete();
            $table->string('seat_number', 5);
            $table->integer('seat_row');
            $table->string('seat_column', 1);
            $table->timestamps();

            $table->unique(['train_class_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
