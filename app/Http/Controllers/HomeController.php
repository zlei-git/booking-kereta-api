<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Train;
use App\Models\TrainSchedule;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stations = Station::where('is_active', true)->orderBy('name')->get();

        $defaultOrigin = $stations->firstWhere('code', 'GMR') ?? $stations->first();
        $defaultDestination = $stations->firstWhere('code', 'BD') ?? $stations->skip(1)->first();

        $popularRoutes = [
            ['from' => 'Jakarta (Gambir)', 'to' => 'Bandung', 'origin_id' => 1, 'dest_id' => 3, 'price' => 75000, 'duration' => '2j 30m'],
            ['from' => 'Jakarta (Gambir)', 'to' => 'Yogyakarta', 'origin_id' => 1, 'dest_id' => 7, 'price' => 200000, 'duration' => '7j 30m'],
            ['from' => 'Jakarta (Gambir)', 'to' => 'Surabaya', 'origin_id' => 1, 'dest_id' => 10, 'price' => 250000, 'duration' => '9j 00m'],
            ['from' => 'Surabaya', 'to' => 'Malang', 'origin_id' => 10, 'dest_id' => 12, 'price' => 50000, 'duration' => '1j 45m'],
            ['from' => 'Jakarta (Gambir)', 'to' => 'Semarang', 'origin_id' => 1, 'dest_id' => 6, 'price' => 150000, 'duration' => '5j 30m'],
            ['from' => 'Yogyakarta', 'to' => 'Solo', 'origin_id' => 7, 'dest_id' => 8, 'price' => 45000, 'duration' => '1j 00m'],
        ];

        $featuredTrains = Train::where('is_active', true)->with('classes')->take(4)->get();

        return view('home', compact('stations', 'popularRoutes', 'featuredTrains', 'defaultOrigin', 'defaultDestination'));
    }

    public function services()
    {
        $trains = Train::where('is_active', true)->with('classes')->get();
        return view('pages.services', compact('trains'));
    }

    public function guide()
    {
        return view('pages.guide');
    }

    public function promotions()
    {
        $promotions = \App\Models\Promotion::where('is_active', true)->orderBy('created_at', 'desc')->get();
        return view('pages.promotions', compact('promotions'));
    }

    public function validatePromotion(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|integer|min:0',
        ]);

        $code = strtoupper(trim($request->code));
        $promo = \App\Models\Promotion::where('code', $code)->first();

        if (!$promo) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak ditemukan.',
            ], 404);
        }

        if (!$promo->isValidForAmount($request->amount)) {
            $msg = 'Kode promo tidak memenuhi syarat.';
            if ($request->amount < $promo->min_spend) {
                $msg = 'Minimal transaksi untuk promo ini adalah Rp ' . number_format($promo->min_spend, 0, ',', '.');
            } elseif ($promo->used_count >= $promo->usage_limit) {
                $msg = 'Kuota penggunaan kode promo ini telah habis.';
            }
            return response()->json([
                'valid' => false,
                'message' => $msg,
            ], 422);
        }

        $discount = $promo->calculateDiscount($request->amount);

        return response()->json([
            'valid' => true,
            'code' => $promo->code,
            'title' => $promo->title,
            'discount' => $discount,
            'message' => "Promo '{$promo->code}' berhasil diterapkan! Hemat Rp " . number_format($discount, 0, ',', '.'),
        ]);
    }
}
