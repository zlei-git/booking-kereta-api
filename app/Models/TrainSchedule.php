<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainSchedule extends Model
{
    protected $fillable = [
        'train_id', 'origin_station_id', 'destination_station_id',
        'departure_time', 'arrival_time', 'travel_date',
        'class_type', 'base_price', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    public function originStation()
    {
        return $this->belongsTo(Station::class, 'origin_station_id');
    }

    public function destinationStation()
    {
        return $this->belongsTo(Station::class, 'destination_station_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }

    public function getDurationAttribute(): string
    {
        $dep = \Carbon\Carbon::parse($this->departure_time);
        $arr = \Carbon\Carbon::parse($this->arrival_time);
        if ($arr->lt($dep)) {
            $arr->addDay();
        }
        $diff = $dep->diff($arr);
        return $diff->h . 'j ' . $diff->i . 'm';
    }

    public function getDurationMinutesAttribute(): int
    {
        $dep = \Carbon\Carbon::parse($this->departure_time);
        $arr = \Carbon\Carbon::parse($this->arrival_time);
        if ($arr->lt($dep)) {
            $arr->addDay();
        }
        return $dep->diffInMinutes($arr);
    }

    public function getBookedSeatIds(): array
    {
        return BookingPassenger::whereHas('booking', function ($q) {
            $q->where('schedule_id', $this->id)
              ->whereIn('status', ['pending', 'confirmed', 'completed']);
        })->pluck('seat_id')->toArray();
    }

    public function getAvailableSeatsCount(): int
    {
        $trainClass = $this->train->classes()->where('class_type', $this->class_type)->first();
        if (!$trainClass) return 0;
        $totalSeats = $trainClass->seats()->count();
        $bookedCount = count($this->getBookedSeatIds());
        return max(0, $totalSeats - $bookedCount);
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->getAvailableSeatsCount() > 0;
    }
}
