<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainClass extends Model
{
    protected $fillable = ['train_id', 'class_type', 'subclass', 'capacity', 'seats_per_row'];

    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function getClassLabelAttribute(): string
    {
        return match ($this->class_type) {
            'eksekutif' => 'Eksekutif',
            'bisnis' => 'Bisnis',
            'ekonomi' => 'Ekonomi',
            default => $this->class_type,
        };
    }
}
