<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Train;
use App\Models\TrainClass;
use App\Models\Seat;

class TrainSeeder extends Seeder
{
    public function run(): void
    {
        $trains = [
            [
                'name' => 'Argo Wilis',
                'number' => 'KA 17',
                'description' => 'Kereta api penumpang kelas eksekutif dan bisnis',
                'facilities' => ['wifi', 'ac', 'power_outlet', 'meal_service', 'luggage_storage'],
                'classes' => ['eksekutif', 'bisnis']
            ],
            [
                'name' => 'Argo Lawu',
                'number' => 'KA 13',
                'description' => 'Kereta api penumpang kelas eksekutif',
                'facilities' => ['wifi', 'ac', 'power_outlet', 'meal_service', 'luggage_storage'],
                'classes' => ['eksekutif']
            ],
            [
                'name' => 'Taksaka',
                'number' => 'KA 1',
                'description' => 'Kereta api penumpang kelas eksekutif dan bisnis',
                'facilities' => ['wifi', 'ac', 'power_outlet', 'meal_service'],
                'classes' => ['eksekutif', 'bisnis']
            ],
            [
                'name' => 'Sancaka',
                'number' => 'KA 86',
                'description' => 'Kereta api campuran',
                'facilities' => ['ac', 'power_outlet', 'luggage_storage'],
                'classes' => ['eksekutif', 'bisnis', 'ekonomi']
            ],
            [
                'name' => 'Gajayana',
                'number' => 'KA 73',
                'description' => 'Kereta api penumpang kelas eksekutif dan bisnis',
                'facilities' => ['wifi', 'ac', 'power_outlet', 'meal_service', 'luggage_storage'],
                'classes' => ['eksekutif', 'bisnis']
            ],
            [
                'name' => 'Mataram Jaya',
                'number' => 'KA 103',
                'description' => 'Kereta api penumpang kelas bisnis dan ekonomi',
                'facilities' => ['ac', 'luggage_storage'],
                'classes' => ['bisnis', 'ekonomi']
            ],
            [
                'name' => 'Bangunkarta',
                'number' => 'KA 89',
                'description' => 'Kereta api campuran',
                'facilities' => ['ac', 'power_outlet', 'luggage_storage'],
                'classes' => ['eksekutif', 'bisnis', 'ekonomi']
            ],
            [
                'name' => 'Mutiara Selatan',
                'number' => 'KA 165',
                'description' => 'Kereta api penumpang kelas bisnis dan ekonomi',
                'facilities' => ['ac', 'luggage_storage'],
                'classes' => ['bisnis', 'ekonomi']
            ],
            [
                'name' => 'Bima',
                'number' => 'KA 44',
                'description' => 'Kereta api ekspres malam eksekutif',
                'facilities' => ['wifi', 'ac', 'power_outlet', 'meal_service', 'luggage_storage'],
                'classes' => ['eksekutif']
            ],
            [
                'name' => 'Turangga',
                'number' => 'KA 65',
                'description' => 'Kereta api ekspres antarkota lintas selatan',
                'facilities' => ['wifi', 'ac', 'power_outlet', 'meal_service', 'luggage_storage'],
                'classes' => ['eksekutif', 'bisnis']
            ],
            [
                'name' => 'Lodaya',
                'number' => 'KA 92',
                'description' => 'Kereta api penghubung Bandung - Solo',
                'facilities' => ['ac', 'power_outlet', 'luggage_storage'],
                'classes' => ['eksekutif', 'ekonomi']
            ],
            [
                'name' => 'Malabar',
                'number' => 'KA 121',
                'description' => 'Kereta api rute Bandung - Malang',
                'facilities' => ['ac', 'power_outlet', 'luggage_storage'],
                'classes' => ['eksekutif', 'bisnis', 'ekonomi']
            ],
            [
                'name' => 'Jayabaya',
                'number' => 'KA 106',
                'description' => 'Kereta api rute Jakarta Pasar Senen - Malang',
                'facilities' => ['ac', 'power_outlet', 'luggage_storage'],
                'classes' => ['eksekutif', 'ekonomi']
            ],
            [
                'name' => 'Blambangan Ekspres',
                'number' => 'KA 185',
                'description' => 'Kereta api rute Jakarta - Banyuwangi',
                'facilities' => ['ac', 'power_outlet', 'luggage_storage'],
                'classes' => ['eksekutif', 'ekonomi']
            ],
        ];

        $classConfig = [
            'eksekutif' => ['capacity' => 50, 'seats_per_row' => 4, 'rows' => 13],
            'bisnis' => ['capacity' => 64, 'seats_per_row' => 4, 'rows' => 16],
            'ekonomi' => ['capacity' => 80, 'seats_per_row' => 4, 'rows' => 20],
        ];

        $columns = ['A', 'B', 'C', 'D'];

        foreach ($trains as $trainData) {
            $classes = $trainData['classes'];
            unset($trainData['classes']);
            $trainData['is_active'] = true;

            $train = Train::create($trainData);

            foreach ($classes as $classType) {
                $config = $classConfig[$classType];
                
                $trainClass = TrainClass::create([
                    'train_id' => $train->id,
                    'class_type' => $classType,
                    'subclass' => 'A', // Assuming subclass A as default
                    'capacity' => $config['capacity'],
                    'seats_per_row' => $config['seats_per_row'],
                ]);

                $seatCount = 0;
                for ($row = 1; $row <= $config['rows']; $row++) {
                    foreach ($columns as $col) {
                        if ($seatCount >= $config['capacity']) {
                            break 2;
                        }

                        Seat::create([
                            'train_class_id' => $trainClass->id,
                            'seat_number' => $row . $col,
                            'seat_row' => $row,
                            'seat_column' => $col,
                        ]);
                        $seatCount++;
                    }
                }
            }
        }
    }
}
