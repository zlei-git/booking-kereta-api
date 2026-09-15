<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainSchedule;
use App\Models\Train;
use App\Models\Station;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = TrainSchedule::with(['train', 'originStation', 'destinationStation'])
            ->orderByDesc('travel_date')
            ->orderBy('departure_time')
            ->paginate(15);
            
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $trains = Train::where('is_active', true)->orderBy('name')->get();
        $stations = Station::orderBy('name')->get();
        
        return view('admin.schedules.create', compact('trains', 'stations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,id',
            'origin_station_id' => 'required|exists:stations,id',
            'destination_station_id' => 'required|exists:stations,id|different:origin_station_id',
            'travel_date' => 'required|date',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'class_type' => 'required|string',
            'base_price' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
        ], [
            'train_id.required' => 'Kereta wajib dipilih.',
            'origin_station_id.required' => 'Stasiun asal wajib dipilih.',
            'destination_station_id.required' => 'Stasiun tujuan wajib dipilih.',
            'destination_station_id.different' => 'Stasiun tujuan tidak boleh sama dengan stasiun asal.',
            'travel_date.required' => 'Tanggal perjalanan wajib diisi.',
            'departure_time.required' => 'Waktu keberangkatan wajib diisi.',
            'arrival_time.required' => 'Waktu kedatangan wajib diisi.',
            'class_type.required' => 'Kelas wajib diisi.',
            'base_price.required' => 'Harga dasar wajib diisi.',
            'base_price.min' => 'Harga dasar minimal 1.',
            'capacity.required' => 'Kapasitas wajib diisi.',
        ]);

        TrainSchedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal kereta berhasil ditambahkan.');
    }

    public function edit(TrainSchedule $schedule)
    {
        $trains = Train::where('is_active', true)->orderBy('name')->get();
        $stations = Station::orderBy('name')->get();
        
        return view('admin.schedules.edit', compact('schedule', 'trains', 'stations'));
    }

    public function update(Request $request, TrainSchedule $schedule)
    {
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,id',
            'origin_station_id' => 'required|exists:stations,id',
            'destination_station_id' => 'required|exists:stations,id|different:origin_station_id',
            'travel_date' => 'required|date',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'class_type' => 'required|string',
            'base_price' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
        ], [
            'train_id.required' => 'Kereta wajib dipilih.',
            'origin_station_id.required' => 'Stasiun asal wajib dipilih.',
            'destination_station_id.required' => 'Stasiun tujuan wajib dipilih.',
            'destination_station_id.different' => 'Stasiun tujuan tidak boleh sama dengan stasiun asal.',
            'travel_date.required' => 'Tanggal perjalanan wajib diisi.',
            'departure_time.required' => 'Waktu keberangkatan wajib diisi.',
            'arrival_time.required' => 'Waktu kedatangan wajib diisi.',
            'class_type.required' => 'Kelas wajib diisi.',
            'base_price.required' => 'Harga dasar wajib diisi.',
            'base_price.min' => 'Harga dasar minimal 1.',
            'capacity.required' => 'Kapasitas wajib diisi.',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal kereta berhasil diperbarui.');
    }

    public function destroy(TrainSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal kereta berhasil dihapus.');
    }
}
