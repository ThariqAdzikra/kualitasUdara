<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\AirQualityLog;
use Carbon\Carbon;

class IoTController extends Controller
{
    public function index()
    {
        $channelID = "3097032";
        $readAPIKey = "35NWPOHEUVD0XP66";

        // Ambil data terakhir dari ThingSpeak
        $response = Http::get("https://api.thingspeak.com/channels/{$channelID}/feeds.json", [
            'api_key' => $readAPIKey,
            'results' => 1
        ]);

        $data = $response->json();
        $feed = $data['feeds'][0] ?? [];

        // Mapping kategori
        $kategoriList = [
            1 => "Baik",
            2 => "Sedang",
            3 => "Tidak Sehat",
            4 => "Berbahaya",
        ];

        // Mapping arah
        $arahList = [
            -1 => "Turun",
            0 => "Stabil",
            1 => "Naik",
        ];

        $ppm = $feed['field1'] ?? 0;
        $kategori = $feed['field2'] ?? 0;
        $arah = $feed['field3'] ?? 0;

        // Simpan ke database
        if ($ppm > 0) {
            AirQualityLog::create([
                'ppm' => $ppm,
                'kategori' => $kategori,
                'arah' => $arah,
                'recorded_at' => now()
            ]);
        }

        return view('iot', [
            'ppm' => $ppm,
            'kategori' => $kategoriList[$kategori] ?? "Unknown",
            'arah' => $arahList[$arah] ?? "Unknown",
            'kategoriCode' => $kategori,
            'arahCode' => $arah
        ]);
    }

    public function dashboard()
    {
        // Ambil data 24 jam terakhir untuk grafik
        $last24Hours = AirQualityLog::where('recorded_at', '>=', Carbon::now()->subHours(24))
            ->orderBy('recorded_at', 'asc')
            ->get();

        // Group by hour untuk chart
        $hourlyData = AirQualityLog::where('recorded_at', '>=', Carbon::now()->subHours(24))
            ->get()
            ->groupBy(function ($item) {
                return $item->recorded_at->format('Y-m-d H:00');
            })
            ->map(function ($items) {
                return [
                    'avg_ppm' => round($items->avg('ppm'), 2),
                    'count' => $items->count()
                ];
            });

        // Statistik
        $stats = [
            'current' => AirQualityLog::latest('recorded_at')->first(),
            'avg_24h' => round(AirQualityLog::where('recorded_at', '>=', Carbon::now()->subHours(24))->avg('ppm'), 2),
            'max_24h' => round(AirQualityLog::where('recorded_at', '>=', Carbon::now()->subHours(24))->max('ppm'), 2),
            'min_24h' => round(AirQualityLog::where('recorded_at', '>=', Carbon::now()->subHours(24))->min('ppm'), 2),
        ];

        return view('dashboard', [
            'hourlyData' => $hourlyData,
            'stats' => $stats
        ]);
    }

    public function getData()
    {
        // API endpoint untuk real-time update
        $channelID = "3097032";
        $readAPIKey = "35NWPOHEUVD0XP66";

        $response = Http::get("https://api.thingspeak.com/channels/{$channelID}/feeds.json", [
            'api_key' => $readAPIKey,
            'results' => 1
        ]);

        $data = $response->json();
        $feed = $data['feeds'][0] ?? [];

        return response()->json([
            'ppm' => isset($feed['field1']) ? round($feed['field1'], 2) : 0,
            'kategori' => $feed['field2'] ?? 0,
            'arah' => $feed['field3'] ?? 0,
        ]);
    }
}