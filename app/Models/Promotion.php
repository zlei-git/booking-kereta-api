<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'title', 'code', 'description', 'discount_type',
        'value', 'min_spend', 'max_discount', 'start_date',
        'end_date', 'usage_limit', 'used_count', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function isValidForAmount(int $amount): bool
    {
        if (!$this->is_active) return false;
        if ($this->start_date && now()->lt($this->start_date)) return false;
        if ($this->end_date && now()->gt($this->end_date)) return false;
        if ($this->used_count >= $this->usage_limit) return false;
        if ($amount < $this->min_spend) return false;
        return true;
    }

    public function calculateDiscount(int $amount): int
    {
        if (!$this->isValidForAmount($amount)) return 0;

        if ($this->discount_type === 'percentage') {
            $discount = (int) round(($amount * $this->value) / 100);
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            return $discount;
        }

        return min($this->value, $amount);
    }
}
