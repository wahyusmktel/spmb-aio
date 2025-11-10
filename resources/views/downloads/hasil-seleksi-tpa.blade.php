<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Seleksi TPA</title>
    <style>
        /* (CSS sama seperti hasil buta warna) */
        @page {
            margin: 40px 50px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
        }

        .kop-surat {
            text-align: center;
            width: 100%;
            border-bottom: 2px solid #000;
            ...
        }

        .kop-surat h1 {
            font-size: 18px;
            ...
        }

        .kop-surat p {
            font-size: 12px;
            ...
        }

        .judul-utama {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-utama h2 {
            font-size: 16px;
            ...
        }

        .info-kegiatan {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-kegiatan td {
            padding: 3px 0;
            font-size: 12px;
        }

        .info-kegiatan td.label {
            width: 150px;
        }

        .tabel-hasil {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .tabel-hasil th,
        .tabel-hasil td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .tabel-hasil th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .tabel-hasil td.nomor {
            width: 30px;
            text-align: center;
        }

        .tabel-hasil td.nilai {
            width: 60px;
            text-align: center;
            font-weight: bold;
        }

        .signature-block {
            width: 300px;
            margin-top: 40px;
            margin-left: 60%;
            ...
        }
    </style>
</head>

<body>
    <table class="kop-surat">... (KOP SURAT) ...</table>
    <div class="judul-utama">
        <h2>HASIL SELEKSI TES POTENSI AKADEMIK (TPA)</h2>
    </div>
    <table class="info-kegiatan">
        <tr>
            <td class="label">Nama Kegiatan</td>
            <td>: {{ $jadwal->judul_kegiatan }}</td>
        </tr>
        <tr>
            <td class="label">Waktu Pelaksanaan</td>
            <td>: {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai_pelaksanaan)->isoFormat('D MMMM YYYY') }}</td>
        </tr>
    </table>

    <table class="tabel-hasil">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Pendaftaran</th>
                <th>Nama Peserta</th>
                <th>Akun CBT (Username)</th>
                <th>Status Tes</th>
                <th>Nilai TPA (Skala 100)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peserta as $p)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $p->nomor_pendaftaran ?? 'N/A' }}</td>
                    <td>{{ $p->nama_pendaftar ?? 'N/A' }}</td>
                    <td>{{ $p->akunCbt->username ?? 'N/A' }}</td>
                    <td style="text-align: center;">
                        @if ($p->status_tes_tpa)
                            <span style="color: green; font-weight: bold;">Selesai</span>
                        @else
                            <span style="color: red;">Belum Mengerjakan</span>
                        @endif
                    </td>
                    <td class="nilai">
                        {{ $p->nilai_tes_tpa ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="signature-block">... (TANDA TANGAN) ...</div>
</body>

</html>
