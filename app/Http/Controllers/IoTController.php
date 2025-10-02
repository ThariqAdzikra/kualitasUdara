<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\AirQualityLog;
use Carbon\Carbon;

class IoTController extends Controller
{
    // Fungsi helper untuk mendapatkan kategori berdasarkan PPM
    private function getKategoriFromPpm($ppm)
    {
        if ($ppm <= 800)
            return ['code' => 1, 'text' => "Baik"];
        if ($ppm <= 1000)
            return ['code' => 2, 'text' => "Normal"];
        if ($ppm <= 2000)
            return ['code' => 3, 'text' => "Kurang Baik"];
        if ($ppm <= 5000)
            return ['code' => 4, 'text' => "Buruk"];
        return ['code' => 5, 'text' => "Bahaya"];
    }

    public function index()
    {
        $channelID = "3097032";
        $readAPIKey = "35NWPOHEUVD0XP66";

        $response = Http::get("https://api.thingspeak.com/channels/{$channelID}/feeds.json", [
            'api_key' => $readAPIKey,
            'results' => 1
        ]);

        $data = $response->json();
        $feed = $data['feeds'][0] ?? [];

        // Mapping kategori BARU (disesuaikan dengan Arduino)
        $kategoriList = [
            1 => "Baik",
            2 => "Normal",
            3 => "Kurang Baik",
            4 => "Buruk",
            5 => "Bahaya",
        ];

        // Mapping arah tetap sama
        $arahList = [
            -1 => "Turun",
            0 => "Stabil",
            1 => "Naik",
        ];

        $ppm = $feed['field1'] ?? 0;
        $kategoriCode = $feed['field2'] ?? 1; // Default ke 1 jika tidak ada data
        $arahCode = $feed['field3'] ?? 0;

        // Simpan ke database jika ada data yang valid
        if (isset($feed['field1'])) {
            AirQualityLog::create([
                'ppm' => $ppm,
                'kategori' => $kategoriCode,
                'arah' => $arahCode,
                'recorded_at' => Carbon::parse($feed['created_at'])->setTimezone('Asia/Jakarta')
            ]);
        }

        return view('iot', [
            'ppm' => $ppm,
            'kategori' => $kategoriList[$kategoriCode] ?? "Unknown",
            'arah' => $arahList[$arahCode] ?? "Unknown",
            'kategoriCode' => $kategoriCode,
            'arahCode' => $arahCode
        ]);
    }

    public function dashboard()
    {
        // Group by hour untuk line chart
        $hourlyData = AirQualityLog::where('recorded_at', '>=', Carbon::now()->subHours(24))
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->recorded_at)->format('Y-m-d H:00');
            })
            ->map(function ($items) {
                return [
                    'avg_ppm' => round($items->avg('ppm'), 2),
                    'count' => $items->count()
                ];
            });

        // Ambil semua data 24 jam terakhir untuk kalkulasi
        $logs24h = AirQualityLog::where('recorded_at', '>=', Carbon::now()->subHours(24))->get();

        // **BARU: Kalkulasi distribusi status di backend**
        $statusDistribution = [
            'Baik' => 0,
            'Normal' => 0,
            'Kurang Baik' => 0,
            'Buruk' => 0,
            'Bahaya' => 0
        ];
        foreach ($logs24h as $log) {
            $kategori = $this->getKategoriFromPpm($log->ppm)['text'];
            if (isset($statusDistribution[$kategori])) {
                $statusDistribution[$kategori]++;
            }
        }

        // Statistik
        $stats = [
            'current' => AirQualityLog::latest('recorded_at')->first(),
            'avg_24h' => $logs24h->avg('ppm') ? round($logs24h->avg('ppm'), 2) : 0,
            'max_24h' => $logs24h->max('ppm') ? round($logs24h->max('ppm'), 2) : 0,
            'min_24h' => $logs24h->min('ppm') ? round($logs24h->min('ppm'), 2) : 0,
        ];

        return view('dashboard', [
            'hourlyData' => $hourlyData,
            'stats' => $stats,
            'statusDistribution' => $statusDistribution // Kirim data distribusi ke view
        ]);
    }

    public function getData()
    {
        $channelID = "3097032";
        $readAPIKey = "35NWPOHEUVD0XP66";

        $response = Http::get("https://api.thingspeak.com/channels/{$channelID}/feeds.json", [
            'api_key' => $readAPIKey,
            'results' => 1
        ]);

        $data = $response->json();
        $feed = $data['feeds'][0] ?? [];

        return response()->json([
            'ppm' => isset($feed['field1']) ? number_format($feed['field1'], 2) : 0,
            'kategori' => $feed['field2'] ?? 1,
            'arah' => $feed['field3'] ?? 0,
        ]);
    }
}