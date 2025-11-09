<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PesertaSeleksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // === 1. PERSIAPAN FILTER ===

        // Ambil bulan & tahun terpilih. Default-nya adalah bulan & tahun SEKARANG.
        $selectedYear = $request->input('tahun', Carbon::now()->year);
        $selectedMonth = $request->input('bulan', Carbon::now()->month);

        // Buat data untuk dropdown filter (12 bulan ke belakang)
        $filterBulan = [];
        for ($i = 0; $i <= 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $filterBulan[] = [
                'tahun' => $date->year,
                'bulan' => $date->month,
                'nama' => $date->isoFormat('MMMM YYYY')
            ];
        }

        // === 2. LOGIKA DATA GRAFIK ===

        // Tentukan tanggal mulai dan akhir dari bulan terpilih
        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;
        $monthShortName = $startDate->shortMonthName;

        // Buat template data 5 minggu (untuk mengisi data 0)
        $mingguTemplate = [
            'Minggu 1' => 0,
            'Minggu 2' => 0,
            'Minggu 3' => 0,
            'Minggu 4' => 0,
            'Minggu 5' => 0,
        ];

        // Buat label (Caption Bar) sesuai permintaan #3
        $labels = [
            "01-07 {$monthShortName}", // Minggu 1
            "08-14 {$monthShortName}", // Minggu 2
            "15-21 {$monthShortName}", // Minggu 3
            "22-28 {$monthShortName}", // Minggu 4
        ];

        // Tentukan label untuk minggu ke-5 (fleksibel)
        if ($daysInMonth == 29) {
            $labels[] = "29 {$monthShortName}";
        } elseif ($daysInMonth == 30) {
            $labels[] = "29-30 {$monthShortName}";
        } else {
            $labels[] = "29-31 {$monthShortName}";
        }


        // 3. QUERY DATABASE
        // Kita pakai query CASE WHEN untuk mengelompokkan hari ke minggu
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
            ->pluck('total_peserta', 'minggu'); // Hasilnya: ['Minggu 1' => 10, 'Minggu 3' => 5]

        // 4. Gabungkan template 0 dengan data asli
        $dataFinal = array_merge($mingguTemplate, $pesertaPerMinggu->toArray());

        // Data final untuk dikirim ke chart
        $chartData = [
            'labels' => $labels,
            'data' => array_values($dataFinal), // Ambil nilainya [10, 0, 5, 0, 0]
        ];

        // dd($chartData); // Cek data jika perlu

        return view('dashboard', compact('chartData', 'filterBulan', 'selectedYear', 'selectedMonth'));
    }
}
