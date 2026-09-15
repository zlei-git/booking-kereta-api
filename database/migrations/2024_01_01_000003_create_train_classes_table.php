<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('train_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_id')->constrained()->cascadeOnDelete();
            $table->enum('class_type', ['ekonomi', 'bisnis', 'eksekutif']);
            $table->string('subclass', 10)->nullable();
            $table->integer('capacity');
            $table->integer('seats_per_row')->default(4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('train_classes');
    }
};
