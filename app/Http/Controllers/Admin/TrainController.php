<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Train;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrainController extends Controller
{
    public function index()
    {
        $trains = Train::orderBy('name')->paginate(10);
        return view('admin.trains.index', compact('trains'));
    }

    public function create()
    {
        return view('admin.trains.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:50|unique:trains',
            'is_active' => 'boolean',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string'
        ], [
            'name.required' => 'Nama kereta wajib diisi.',
            'number.required' => 'Nomor kereta wajib diisi.',
            'number.unique' => 'Nomor kereta sudah terdaftar.',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['facilities'] = $request->facilities ?? [];

        Train::create($validated);

        return redirect()->route('admin.trains.index')
            ->with('success', 'Kereta berhasil ditambahkan.');
    }

    public function edit(Train $train)
    {
        return view('admin.trains.edit', compact('train'));
    }

    public function update(Request $request, Train $train)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => ['required', 'string', 'max:50', Rule::unique('trains')->ignore($train->id)],
            'is_active' => 'boolean',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string'
        ], [
            'name.required' => 'Nama kereta wajib diisi.',
            'number.required' => 'Nomor kereta wajib diisi.',
            'number.unique' => 'Nomor kereta sudah terdaftar.',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['facilities'] = $request->facilities ?? [];

        $train->update($validated);

        return redirect()->route('admin.trains.index')
            ->with('success', 'Kereta berhasil diperbarui.');
    }

    public function destroy(Train $train)
    {
        $train->delete();

        return redirect()->route('admin.trains.index')
            ->with('success', 'Kereta berhasil dihapus.');
    }
}
