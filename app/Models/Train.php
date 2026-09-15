<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    protected $fillable = ['name', 'number', 'description', 'facilities', 'is_active'];

    protected function casts(): array
    {
        return [
            'facilities' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function classes()
    {
        return $this->hasMany(TrainClass::class);
    }

    public function schedules()
    {
        return $this->hasMany(TrainSchedule::class);
    }

    public function getTrainNumberAttribute(): string
    {
        return $this->number ?? '';
    }

    public function setTrainNumberAttribute($value): void
    {
        $this->attributes['number'] = $value;
    }
}
