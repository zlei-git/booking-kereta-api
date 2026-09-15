<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'title' => 'Weekend Escape',
                'code' => 'WEEKEND15',
                'description' => 'Hemat hingga 15% untuk pemesanan perjalanan akhir pekan.',
                'discount_type' => 'percentage',
                'value' => 15,
                'min_spend' => 150000,
                'max_discount' => 100000,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addMonths(2),
                'usage_limit' => 500,
                'is_active' => true,
            ],
            [
                'title' => 'Nordic Welcome',
                'code' => 'NORDIC10',
                'description' => 'Potongan 10% untuk perjalanan perdana bersama NordicRail.',
                'discount_type' => 'percentage',
                'value' => 10,
                'min_spend' => 100000,
                'max_discount' => 75000,
                'start_date' => now()->subDays(10),
                'end_date' => now()->addMonths(3),
                'usage_limit' => 1000,
                'is_active' => true,
            ],
            [
                'title' => 'Potongan Rute Eksekutif',
                'code' => 'HEMAT50K',
                'description' => 'Potongan langsung Rp 50.000 untuk perjalanan kelas Eksekutif & Luxury.',
                'discount_type' => 'fixed',
                'value' => 50000,
                'min_spend' => 300000,
                'max_discount' => 50000,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addMonths(1),
                'usage_limit' => 300,
                'is_active' => true,
            ],
        ];

        foreach ($promotions as $promo) {
            Promotion::updateOrCreate(['code' => $promo['code']], $promo);
        }
    }
}
