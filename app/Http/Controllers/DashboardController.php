<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PesertaSeleksi;
use App\Models\JadwalSeleksi;
use App\Models\Guru;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // === 1. PERSIAPAN FILTER ===
        $selectedYear = $request->input('tahun', Carbon::now()->year);
        $selectedMonth = $request->input('bulan', Carbon::now()->month);

        $filterBulan = [];
        for ($i = 0; $i <= 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $filterBulan[] = [
                'tahun' => $date->year,
                'bulan' => $date->month,
                'nama' => $date->isoFormat('MMMM YYYY')
            ];
        }

        // === 2. LOGIKA GRAFIK (Chart) ===
        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $daysInMonth = $startDate->daysInMonth;
        $monthShortName = $startDate->shortMonthName;

        $mingguTemplate = [
            'Minggu 1' => 0,
            'Minggu 2' => 0,
            'Minggu 3' => 0,
            'Minggu 4' => 0,
            'Minggu 5' => 0,
        ];

        $labels = [
            "01-07 {$monthShortName}",
            "08-14 {$monthShortName}",
            "15-21 {$monthShortName}",
            "22-28 {$monthShortName}",
        ];

        if ($daysInMonth == 29) $labels[] = "29 {$monthShortName}";
        elseif ($daysInMonth == 30) $labels[] = "29-30 {$monthShortName}";
        else $labels[] = "29-31 {$monthShortName}";

        $pesertaPerMinggu = PesertaSeleksi::query()
            ->select(
                DB::raw("CASE
                            WHEN DAY(created_at) BETWEEN 1 AND 7 THEN 'Minggu 1'
                            WHEN DAY(created_at) BETWEEN 8 AND 14 THEN 'Minggu 2'
                            WHEN DAY(created_at) BETWEEN 15 AND 21 THEN 'Minggu 3'
                            WHEN DAY(created_at) BETWEEN 22 AND 28 THEN 'Minggu 4'
                            WHEN DAY(created_at) >= 29 THEN 'Minggu 5'
                        END as minggu"),
                DB::raw('COUNT(*) as total_peserta')
            )
            ->whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $selectedMonth)
            ->groupBy('minggu')
            ->get()
            ->pluck('total_peserta', 'minggu');

        $dataFinal = array_merge($mingguTemplate, $pesertaPerMinggu->toArray());

        $chartData = [
            'labels' => $labels,
            'data' => array_values($dataFinal),
        ];

        // === 3. LOGIKA STAT CARDS (BARU) ===
        $stats = [
            'total_peserta' => PesertaSeleksi::count(),
            'total_jadwal_terbit' => JadwalSeleksi::where('status', 'diterbitkan')->count(),
            'total_guru_aktif' => Guru::whereNotNull('user_id')->count(),
        ];

        return view('dashboard', compact('chartData', 'filterBulan', 'selectedYear', 'selectedMonth', 'stats'));
    }
}
