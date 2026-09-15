<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'schedule_id', 'booking_number',
        'passenger_count', 'base_price', 'service_fee',
        'addon_fee', 'discount_amount', 'promo_code',
        'total_price', 'status', 'expired_at',
    ];

    protected function casts(): array
    {
        return ['expired_at' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(TrainSchedule::class, 'schedule_id');
    }

    public function passengers()
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public static function generateBookingNumber(): string
    {
        $year = now()->format('Y');
        $lastBooking = static::where('booking_number', 'like', "NR-{$year}-%")
            ->orderByDesc('id')
            ->first();

        if ($lastBooking) {
            $parts = explode('-', $lastBooking->booking_number);
            $lastSeq = (int) end($parts);
            $newSeq = str_pad($lastSeq + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newSeq = '000128';
        }

        return "NR-{$year}-{$newSeq}";
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'confirmed' => 'Terkonfirmasi',
            'preparing' => 'Persiapan Rangkaian',
            'boarding' => 'Boarding Peron',
            'completed' => 'Perjalanan Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'emerald',
            'preparing' => 'blue',
            'boarding' => 'amber',
            'completed' => 'stone',
            'cancelled' => 'red',
            default => 'stone',
        };
    }
}
