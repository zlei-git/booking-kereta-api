<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Train;
use App\Models\TrainSchedule;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $stations = Station::all()->keyBy('code');
        $trains = Train::with('classes')->get();
        
        $baseRoutes = [
            // Jakarta <-> Bandung
            ['origin' => 'GMR', 'destination' => 'BD', 'dur' => [2.5, 3.0], 'eco' => [75000, 100000], 'bis' => [140000, 180000], 'eks' => [220000, 320000], 'trains' => 3],
            ['origin' => 'PSE', 'destination' => 'BD', 'dur' => [2.8, 3.2], 'eco' => [65000, 85000], 'bis' => [120000, 150000], 'eks' => [190000, 260000], 'trains' => 2],
            ['origin' => 'PSE', 'destination' => 'KAC', 'dur' => [2.8, 3.2], 'eco' => [60000, 80000], 'bis' => [110000, 140000], 'eks' => [180000, 240000], 'trains' => 2],

            // Jakarta <-> Cirebon
            ['origin' => 'GMR', 'destination' => 'CN', 'dur' => [2.5, 3.0], 'eco' => [110000, 140000], 'bis' => [180000, 230000], 'eks' => [250000, 350000], 'trains' => 3],
            ['origin' => 'PSE', 'destination' => 'CN', 'dur' => [2.8, 3.2], 'eco' => [90000, 120000], 'bis' => [150000, 190000], 'eks' => [210000, 290000], 'trains' => 2],

            // Jakarta <-> Purwokerto
            ['origin' => 'GMR', 'destination' => 'PWT', 'dur' => [4.5, 5.2], 'eco' => [150000, 200000], 'bis' => [250000, 310000], 'eks' => [350000, 470000], 'trains' => 2],
            ['origin' => 'PSE', 'destination' => 'PWT', 'dur' => [4.8, 5.5], 'eco' => [130000, 170000], 'bis' => [220000, 280000], 'eks' => [300000, 420000], 'trains' => 2],

            // Jakarta <-> Semarang
            ['origin' => 'GMR', 'destination' => 'SMT', 'dur' => [5.0, 5.8], 'eco' => [170000, 230000], 'bis' => [280000, 360000], 'eks' => [390000, 530000], 'trains' => 3],
            ['origin' => 'PSE', 'destination' => 'SMT', 'dur' => [5.5, 6.2], 'eco' => [140000, 190000], 'bis' => [240000, 310000], 'eks' => [340000, 460000], 'trains' => 2],

            // Jakarta <-> Yogyakarta
            ['origin' => 'GMR', 'destination' => 'YK', 'dur' => [6.5, 7.5], 'eco' => [210000, 290000], 'bis' => [360000, 460000], 'eks' => [480000, 680000], 'trains' => 3],
            ['origin' => 'PSE', 'destination' => 'YK', 'dur' => [7.2, 8.0], 'eco' => [180000, 250000], 'bis' => [310000, 400000], 'eks' => [420000, 580000], 'trains' => 3],

            // Jakarta <-> Solo
            ['origin' => 'GMR', 'destination' => 'SLO', 'dur' => [7.2, 8.2], 'eco' => [220000, 300000], 'bis' => [370000, 470000], 'eks' => [490000, 690000], 'trains' => 2],
            ['origin' => 'PSE', 'destination' => 'SLO', 'dur' => [7.8, 8.8], 'eco' => [190000, 260000], 'bis' => [320000, 410000], 'eks' => [430000, 590000], 'trains' => 2],

            // Jakarta <-> Madiun
            ['origin' => 'GMR', 'destination' => 'MN', 'dur' => [8.0, 9.0], 'eco' => [240000, 310000], 'bis' => [390000, 490000], 'eks' => [510000, 710000], 'trains' => 2],
            ['origin' => 'PSE', 'destination' => 'MN', 'dur' => [8.5, 9.5], 'eco' => [200000, 270000], 'bis' => [340000, 430000], 'eks' => [450000, 610000], 'trains' => 2],

            // Jakarta <-> Surabaya
            ['origin' => 'GMR', 'destination' => 'SGU', 'dur' => [8.8, 10.0], 'eco' => [260000, 340000], 'bis' => [420000, 540000], 'eks' => [560000, 780000], 'trains' => 3],
            ['origin' => 'GMR', 'destination' => 'SBI', 'dur' => [8.2, 9.2], 'eco' => [250000, 330000], 'bis' => [410000, 520000], 'eks' => [540000, 750000], 'trains' => 3],
            ['origin' => 'PSE', 'destination' => 'SGU', 'dur' => [9.5, 11.0], 'eco' => [220000, 290000], 'bis' => [360000, 460000], 'eks' => [480000, 660000], 'trains' => 2],

            // Jakarta <-> Malang
            ['origin' => 'GMR', 'destination' => 'ML', 'dur' => [10.5, 12.0], 'eco' => [280000, 370000], 'bis' => [440000, 570000], 'eks' => [590000, 820000], 'trains' => 2],
            ['origin' => 'PSE', 'destination' => 'ML', 'dur' => [11.2, 12.8], 'eco' => [240000, 310000], 'bis' => [380000, 490000], 'eks' => [510000, 710000], 'trains' => 2],

            // Jakarta <-> Banyuwangi
            ['origin' => 'PSE', 'destination' => 'BWI', 'dur' => [14.0, 16.0], 'eco' => [320000, 420000], 'bis' => [480000, 620000], 'eks' => [650000, 890000], 'trains' => 1],

            // Bandung <-> Cirebon
            ['origin' => 'BD', 'destination' => 'CN', 'dur' => [3.2, 3.8], 'eco' => [85000, 115000], 'bis' => [140000, 180000], 'eks' => [200000, 270000], 'trains' => 2],

            // Bandung <-> Purwokerto
            ['origin' => 'BD', 'destination' => 'PWT', 'dur' => [4.2, 5.0], 'eco' => [110000, 150000], 'bis' => [180000, 230000], 'eks' => [260000, 350000], 'trains' => 2],

            // Bandung <-> Yogyakarta
            ['origin' => 'BD', 'destination' => 'YK', 'dur' => [6.2, 7.2], 'eco' => [160000, 220000], 'bis' => [260000, 340000], 'eks' => [370000, 490000], 'trains' => 3],

            // Bandung <-> Solo
            ['origin' => 'BD', 'destination' => 'SLO', 'dur' => [7.0, 8.0], 'eco' => [180000, 240000], 'bis' => [280000, 360000], 'eks' => [390000, 520000], 'trains' => 2],

            // Bandung <-> Surabaya
            ['origin' => 'BD', 'destination' => 'SGU', 'dur' => [9.5, 11.0], 'eco' => [230000, 310000], 'bis' => [370000, 470000], 'eks' => [500000, 690000], 'trains' => 2],

            // Bandung <-> Malang
            ['origin' => 'BD', 'destination' => 'ML', 'dur' => [11.5, 13.0], 'eco' => [250000, 340000], 'bis' => [400000, 520000], 'eks' => [540000, 740000], 'trains' => 1],

            // Cirebon <-> Semarang
            ['origin' => 'CN', 'destination' => 'SMT', 'dur' => [2.2, 2.8], 'eco' => [80000, 110000], 'bis' => [130000, 170000], 'eks' => [190000, 260000], 'trains' => 2],

            // Cirebon <-> Yogyakarta
            ['origin' => 'CN', 'destination' => 'YK', 'dur' => [4.0, 4.8], 'eco' => [130000, 170000], 'bis' => [210000, 270000], 'eks' => [290000, 390000], 'trains' => 2],

            // Cirebon <-> Surabaya
            ['origin' => 'CN', 'destination' => 'SBI', 'dur' => [5.5, 6.5], 'eco' => [180000, 240000], 'bis' => [280000, 360000], 'eks' => [390000, 520000], 'trains' => 2],

            // Purwokerto <-> Yogyakarta
            ['origin' => 'PWT', 'destination' => 'YK', 'dur' => [2.2, 2.8], 'eco' => [60000, 85000], 'bis' => [100000, 140000], 'eks' => [150000, 210000], 'trains' => 3],

            // Purwokerto <-> Solo
            ['origin' => 'PWT', 'destination' => 'SLO', 'dur' => [3.0, 3.6], 'eco' => [75000, 105000], 'bis' => [120000, 160000], 'eks' => [180000, 250000], 'trains' => 2],

            // Purwokerto <-> Surabaya
            ['origin' => 'PWT', 'destination' => 'SGU', 'dur' => [5.8, 6.8], 'eco' => [160000, 220000], 'bis' => [260000, 340000], 'eks' => [370000, 490000], 'trains' => 2],

            // Semarang <-> Solo
            ['origin' => 'SMT', 'destination' => 'SLO', 'dur' => [1.8, 2.4], 'eco' => [55000, 80000], 'bis' => [90000, 130000], 'eks' => [140000, 190000], 'trains' => 2],

            // Semarang <-> Surabaya
            ['origin' => 'SMT', 'destination' => 'SBI', 'dur' => [3.8, 4.5], 'eco' => [110000, 150000], 'bis' => [180000, 240000], 'eks' => [260000, 350000], 'trains' => 3],

            // Semarang <-> Yogyakarta
            ['origin' => 'SMT', 'destination' => 'YK', 'dur' => [2.2, 2.8], 'eco' => [65000, 95000], 'bis' => [110000, 150000], 'eks' => [160000, 220000], 'trains' => 2],

            // Yogyakarta <-> Solo
            ['origin' => 'YK', 'destination' => 'SLO', 'dur' => [0.8, 1.2], 'eco' => [30000, 45000], 'bis' => [55000, 75000], 'eks' => [85000, 120000], 'trains' => 3],

            // Yogyakarta <-> Madiun
            ['origin' => 'YK', 'destination' => 'MN', 'dur' => [2.0, 2.6], 'eco' => [65000, 90000], 'bis' => [110000, 150000], 'eks' => [160000, 220000], 'trains' => 2],

            // Yogyakarta <-> Surabaya
            ['origin' => 'YK', 'destination' => 'SGU', 'dur' => [4.2, 5.2], 'eco' => [120000, 170000], 'bis' => [200000, 270000], 'eks' => [290000, 390000], 'trains' => 3],

            // Yogyakarta <-> Malang
            ['origin' => 'YK', 'destination' => 'ML', 'dur' => [6.0, 7.0], 'eco' => [150000, 210000], 'bis' => [240000, 320000], 'eks' => [340000, 460000], 'trains' => 2],

            // Yogyakarta <-> Banyuwangi
            ['origin' => 'YK', 'destination' => 'BWI', 'dur' => [10.0, 12.0], 'eco' => [220000, 300000], 'bis' => [340000, 440000], 'eks' => [470000, 640000], 'trains' => 1],

            // Solo <-> Madiun
            ['origin' => 'SLO', 'destination' => 'MN', 'dur' => [1.2, 1.7], 'eco' => [45000, 65000], 'bis' => [75000, 105000], 'eks' => [115000, 160000], 'trains' => 2],

            // Solo <-> Surabaya
            ['origin' => 'SLO', 'destination' => 'SGU', 'dur' => [3.2, 4.0], 'eco' => [100000, 140000], 'bis' => [170000, 230000], 'eks' => [250000, 340000], 'trains' => 2],

            // Solo <-> Malang
            ['origin' => 'SLO', 'destination' => 'ML', 'dur' => [5.0, 6.0], 'eco' => [130000, 180000], 'bis' => [210000, 280000], 'eks' => [300000, 410000], 'trains' => 2],

            // Madiun <-> Surabaya
            ['origin' => 'MN', 'destination' => 'SGU', 'dur' => [2.0, 2.6], 'eco' => [60000, 85000], 'bis' => [100000, 140000], 'eks' => [150000, 210000], 'trains' => 2],

            // Surabaya <-> Malang
            ['origin' => 'SGU', 'destination' => 'ML', 'dur' => [1.8, 2.3], 'eco' => [50000, 75000], 'bis' => [90000, 130000], 'eks' => [140000, 190000], 'trains' => 3],

            // Surabaya <-> Jember
            ['origin' => 'SGU', 'destination' => 'JR', 'dur' => [3.5, 4.2], 'eco' => [90000, 125000], 'bis' => [150000, 200000], 'eks' => [220000, 300000], 'trains' => 2],

            // Surabaya <-> Banyuwangi
            ['origin' => 'SGU', 'destination' => 'BWI', 'dur' => [5.5, 6.5], 'eco' => [140000, 190000], 'bis' => [230000, 300000], 'eks' => [330000, 440000], 'trains' => 2],

            // Malang <-> Jember
            ['origin' => 'ML', 'destination' => 'JR', 'dur' => [3.8, 4.5], 'eco' => [95000, 130000], 'bis' => [160000, 210000], 'eks' => [230000, 310000], 'trains' => 1],

            // Malang <-> Banyuwangi
            ['origin' => 'ML', 'destination' => 'BWI', 'dur' => [6.5, 7.5], 'eco' => [150000, 210000], 'bis' => [240000, 320000], 'eks' => [340000, 460000], 'trains' => 1],

            // Jember <-> Banyuwangi
            ['origin' => 'JR', 'destination' => 'BWI', 'dur' => [2.0, 2.6], 'eco' => [50000, 70000], 'bis' => [80000, 110000], 'eks' => [120000, 170000], 'trains' => 2],
        ];

        // Automatically create return routes (bidirectional) so both directions always have trains
        $allRoutes = [];
        foreach ($baseRoutes as $r) {
            $allRoutes[] = $r;
            // Reverse route
            $reversed = $r;
            $reversed['origin'] = $r['destination'];
            $reversed['destination'] = $r['origin'];
            $allRoutes[] = $reversed;
        }

        // Generate dates: from today (2026-09-14) up to 45 days ahead ONLY on even dates (2, 4, 6, 8, ...)
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(45);
        $currentDate = $startDate->copy();

        $departureTimeSlots = [
            '05:30', '06:15', '07:00', '08:15', '09:30', '10:45',
            '12:00', '13:15', '14:30', '15:45', '17:00', '18:15',
            '19:30', '20:45', '21:30'
        ];

        $now = now();
        $schedulesBatch = [];

        while ($currentDate->lte($endDate)) {
            // Hanya buat jadwal pada tanggal genap (2, 4, 6, 8, ...)
            if ($currentDate->day % 2 !== 0) {
                $currentDate->addDay();
                continue;
            }

            foreach ($allRoutes as $routeIndex => $route) {
                if (!isset($stations[$route['origin']]) || !isset($stations[$route['destination']])) {
                    continue;
                }

                $originId = $stations[$route['origin']]->id;
                $destinationId = $stations[$route['destination']]->id;

                // Select 1 to 3 trains (some routes have 1, some 2, some 3)
                $targetTrainCount = $route['trains'] ?? 2;
                // Vary slightly: e.g. target 3 -> 2-3, target 2 -> 1-2, target 1 -> 1
                $actualTrainCount = ($targetTrainCount == 3) ? rand(2, 3) : (($targetTrainCount == 2) ? rand(1, 2) : 1);

                $selectedTrains = $trains->random(min($actualTrainCount, $trains->count()));

                // Pick distinct departure times for each train on this route
                $shuffledSlots = $departureTimeSlots;
                shuffle($shuffledSlots);

                $tIndex = 0;
                foreach ($selectedTrains as $train) {
                    $slotTime = $shuffledSlots[$tIndex % count($shuffledSlots)];
                    $tIndex++;

                    [$h, $m] = explode(':', $slotTime);
                    $departureTime = $currentDate->copy()->setHour((int)$h)->setMinute((int)$m)->setSecond(0);

                    $durHours = rand((int)($route['dur'][0] * 10), (int)($route['dur'][1] * 10)) / 10;
                    $durationMinutes = (int)($durHours * 60);
                    $arrivalTime = $departureTime->copy()->addMinutes($durationMinutes);

                    foreach ($train->classes as $trainClass) {
                        $priceKey = match ($trainClass->class_type) {
                            'ekonomi' => 'eco',
                            'bisnis' => 'bis',
                            'eksekutif' => 'eks',
                            default => 'eco',
                        };

                        if (!isset($route[$priceKey])) continue;

                        $priceRange = $route[$priceKey];
                        $price = rand((int)($priceRange[0] / 1000), (int)($priceRange[1] / 1000)) * 1000;

                        $schedulesBatch[] = [
                            'train_id' => $train->id,
                            'origin_station_id' => $originId,
                            'destination_station_id' => $destinationId,
                            'departure_time' => $departureTime->toDateTimeString(),
                            'arrival_time' => $arrivalTime->toDateTimeString(),
                            'travel_date' => $currentDate->format('Y-m-d'),
                            'class_type' => $trainClass->class_type,
                            'base_price' => $price,
                            'is_active' => true,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];

                        if (count($schedulesBatch) >= 500) {
                            TrainSchedule::insert($schedulesBatch);
                            $schedulesBatch = [];
                        }
                    }
                }
            }
            $currentDate->addDay();
        }

        if (!empty($schedulesBatch)) {
            TrainSchedule::insert($schedulesBatch);
        }
    }
}

