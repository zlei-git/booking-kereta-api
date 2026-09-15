<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\TrainSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $stations = Station::where('is_active', true)->orderBy('name')->get();
        $schedules = collect();
        $searched = false;

        $origin = $request->get('origin') ?? $request->get('origin_station_id');
        $destination = $request->get('destination') ?? $request->get('destination_station_id');
        $date = $request->get('date') ?? $request->get('travel_date');
        $class = $request->get('class');

        if ($origin || $destination || $date || $class) {
            $searched = true;
            $query = TrainSchedule::with(['train', 'originStation', 'destinationStation'])
                ->where('is_active', true);

            if ($origin) {
                $query->where('origin_station_id', $origin);
            }
            if ($destination) {
                $query->where('destination_station_id', $destination);
            }
            if ($date) {
                $query->where('travel_date', $date);
            }
            if ($class) {
                $query->where('class_type', $class);
            }

            $schedules = $query->orderBy('travel_date')->orderBy('departure_time')->paginate(20);
        }

        return view('schedules.index', compact('stations', 'schedules', 'searched'));
    }
}
