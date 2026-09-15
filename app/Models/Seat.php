<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['train_class_id', 'seat_number', 'seat_row', 'seat_column'];

    public function trainClass()
    {
        return $this->belongsTo(TrainClass::class);
    }

    public function bookingPassengers()
    {
        return $this->hasMany(BookingPassenger::class);
    }
}
