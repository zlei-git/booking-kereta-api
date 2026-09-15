<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id', 'method', 'amount',
        'status', 'va_number', 'reference_number', 'paid_at',
    ];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime'];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            'bank_transfer' => 'Transfer Bank',
            'virtual_account' => 'Virtual Account',
            'ewallet' => 'E-Wallet',
            'qris' => 'QRIS',
            'card' => 'Kartu Kredit / Debit',
            default => $this->method ? ucfirst(str_replace('_', ' ', $this->method)) : '-',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
            'expired' => 'Kedaluwarsa',
            default => $this->status,
        };
    }
}
