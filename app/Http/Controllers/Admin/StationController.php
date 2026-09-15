<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::orderBy('name')->paginate(10);
        return view('admin.stations.index', compact('stations'));
    }

    public function create()
    {
        return view('admin.stations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:stations',
            'city' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama stasiun wajib diisi.',
            'code.required' => 'Kode stasiun wajib diisi.',
            'code.unique' => 'Kode stasiun sudah terdaftar.',
            'city.required' => 'Kota wajib diisi.',
        ]);

        Station::create($validated);

        return redirect()->route('admin.stations.index')
            ->with('success', 'Stasiun berhasil ditambahkan.');
    }

    public function edit(Station $station)
    {
        return view('admin.stations.edit', compact('station'));
    }

    public function update(Request $request, Station $station)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:10', Rule::unique('stations')->ignore($station->id)],
            'city' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama stasiun wajib diisi.',
            'code.required' => 'Kode stasiun wajib diisi.',
            'code.unique' => 'Kode stasiun sudah terdaftar.',
            'city.required' => 'Kota wajib diisi.',
        ]);

        $station->update($validated);

        return redirect()->route('admin.stations.index')
            ->with('success', 'Stasiun berhasil diperbarui.');
    }

    public function destroy(Station $station)
    {
        $station->delete();

        return redirect()->route('admin.stations.index')
            ->with('success', 'Stasiun berhasil dihapus.');
    }
}
