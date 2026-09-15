<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;

class StationSeeder extends Seeder
{
    public function run(): void
    {
        $stations = [
            ['name' => 'Gambir', 'code' => 'GMR', 'city' => 'Jakarta'],
            ['name' => 'Pasar Senen', 'code' => 'PSE', 'city' => 'Jakarta'],
            ['name' => 'Bandung', 'code' => 'BD', 'city' => 'Bandung'],
            ['name' => 'Cirebon', 'code' => 'CN', 'city' => 'Cirebon'],
            ['name' => 'Purwokerto', 'code' => 'PWT', 'city' => 'Purwokerto'],
            ['name' => 'Semarang Tawang', 'code' => 'SMT', 'city' => 'Semarang'],
            ['name' => 'Yogyakarta', 'code' => 'YK', 'city' => 'Yogyakarta'],
            ['name' => 'Solo Balapan', 'code' => 'SLO', 'city' => 'Solo'],
            ['name' => 'Madiun', 'code' => 'MN', 'city' => 'Madiun'],
            ['name' => 'Surabaya Gubeng', 'code' => 'SGU', 'city' => 'Surabaya'],
            ['name' => 'Surabaya Pasar Turi', 'code' => 'SBI', 'city' => 'Surabaya'],
            ['name' => 'Malang', 'code' => 'ML', 'city' => 'Malang'],
            ['name' => 'Jember', 'code' => 'JR', 'city' => 'Jember'],
            ['name' => 'Banyuwangi Kota', 'code' => 'BWI', 'city' => 'Banyuwangi'],
            ['name' => 'Jakarta Kota', 'code' => 'JAKK', 'city' => 'Jakarta'],
            ['name' => 'Kiaracondong', 'code' => 'KAC', 'city' => 'Bandung'],
        ];

        foreach ($stations as $station) {
            Station::create(array_merge($station, ['is_active' => true]));
        }
    }
}
