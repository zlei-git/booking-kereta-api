<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = ['name', 'code', 'city', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function originSchedules()
    {
        return $this->hasMany(TrainSchedule::class, 'origin_station_id');
    }

    public function destinationSchedules()
    {
        return $this->hasMany(TrainSchedule::class, 'destination_station_id');
    }
}
