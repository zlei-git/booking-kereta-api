<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\TrainSchedule;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $stations = Station::where('is_active', true)->orderBy('name')->get();

        if (!$request->has('origin')) {
            $defaultDate = today();
            if ($defaultDate->day % 2 !== 0) {
                $defaultDate = $defaultDate->copy()->addDay();
            }

            $defaultOrigin = $stations->firstWhere('code', 'GMR') ?? $stations->first();
            $defaultDestination = $stations->firstWhere('code', 'BD') ?? $stations->skip(1)->first();

            $schedules = TrainSchedule::with(['train', 'originStation', 'destinationStation'])
                ->where('travel_date', '>=', today())
                ->where('is_active', true)
                ->orderBy('travel_date')
                ->orderBy('departure_time')
                ->take(12)
                ->get();

            return view('search.results', [
                'stations' => $stations,
                'schedules' => $schedules,
                'searched' => false,
                'origin' => $defaultOrigin,
                'destination' => $defaultDestination,
                'date' => $defaultDate->toDateString(),
                'passengers' => 1,
                'classFilter' => null,
                'timeSlot' => null,
                'availability' => 'all',
                'sort' => 'price',
                'isOddDate' => false,
                'nextEvenDate' => null,
                'prevEvenDate' => null,
            ]);
        }

        $request->validate([
            'origin' => 'required|exists:stations,id',
            'destination' => 'required|exists:stations,id|different:origin',
            'date' => 'required|date|after_or_equal:today',
            'passengers' => 'required|integer|min:1|max:5',
            'class' => 'nullable|in:ekonomi,bisnis,eksekutif',
        ]);

        $query = TrainSchedule::with(['train', 'originStation', 'destinationStation'])
            ->where('origin_station_id', $request->origin)
            ->where('destination_station_id', $request->destination)
            ->where('travel_date', $request->date)
            ->where('is_active', true);

        if ($request->filled('class')) {
            $query->where('class_type', $request->class);
        }

        $sort = $request->get('sort', 'price');
        $schedules = match ($sort) {
            'departure' => $query->orderBy('departure_time')->get(),
            'duration' => $query->get()->sortBy('duration_minutes'),
            default => $query->orderBy('base_price')->get(),
        };

        // Filter by price range
        if ($request->filled('price_min')) {
            $schedules = $schedules->where('base_price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $schedules = $schedules->where('base_price', '<=', $request->price_max);
        }

        // Filter by time slot
        if ($request->filled('time_slot')) {
            $schedules = $schedules->filter(function ($s) use ($request) {
                $time = substr($s->departure_time, 0, 5);
                return match ($request->time_slot) {
                    'pagi' => $time < '12:00',
                    'siang' => $time >= '12:00' && $time < '16:00',
                    'sore' => $time >= '16:00' && $time < '19:00',
                    'malam' => $time >= '19:00',
                    default => true,
                };
            });
        }

        // Filter by availability
        if ($request->get('availability') === 'available_only') {
            $schedules = $schedules->filter(function ($s) use ($request) {
                return $s->getAvailableSeatsCount() >= $request->passengers;
            });
        }

        $origin = Station::find($request->origin);
        $destination = Station::find($request->destination);

        $dateCarbon = \Carbon\Carbon::parse($request->date);
        $isOddDate = ($dateCarbon->day % 2 !== 0);
        $nextEvenDate = $dateCarbon->copy()->addDay();
        if ($nextEvenDate->day % 2 !== 0) {
            $nextEvenDate->addDay();
        }
        $prevEvenDate = $dateCarbon->copy()->subDay();
        if ($prevEvenDate->day % 2 !== 0) {
            $prevEvenDate->subDay();
        }
        if ($prevEvenDate->lt(\Carbon\Carbon::today())) {
            $prevEvenDate = null;
        }

        return view('search.results', [
            'stations' => $stations,
            'schedules' => $schedules,
            'origin' => $origin,
            'destination' => $destination,
            'date' => $request->date,
            'passengers' => $request->passengers,
            'classFilter' => $request->class,
            'timeSlot' => $request->time_slot,
            'availability' => $request->get('availability', 'all'),
            'sort' => $sort,
            'searched' => true,
            'isOddDate' => $isOddDate,
            'nextEvenDate' => $nextEvenDate->toDateString(),
            'prevEvenDate' => $prevEvenDate?->toDateString(),
        ]);
    }
}
