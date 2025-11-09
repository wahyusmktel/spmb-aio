<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Hadir Petugas</title>
    <style>
        /* (CSS sama persis seperti laporan peserta di atas) */
        @page {
            margin: 40px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
        }

        .kop-surat {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat .judul-kop {
            text-align: center;
            line-height: 1.4;
        }

        .kop-surat .judul-kop h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .kop-surat .judul-kop p {
            font-size: 12px;
            margin: 0;
        }

        .judul-utama {
            text-align: center;
            margin-bottom: 10px;
        }

        .judul-utama h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-decoration: underline;
        }

        .judul-utama p {
            font-size: 12px;
            margin: 0;
            font-weight: bold;
        }

        .info-kegiatan {
            width: 100%;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .info-kegiatan td.label {
            width: 150px;
        }

        .rekap {
            width: 40%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #000;
        }

        .rekap th {
            background-color: #f0f0f0;
            padding: 6px;
            border: 1px solid #000;
        }

        .rekap td {
            padding: 6px;
            border: 1px solid #000;
        }

        .tabel-hadir {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .tabel-hadir th,
        .tabel-hadir td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .tabel-hadir th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .tabel-hadir td.nomor {
            width: 30px;
            text-align: center;
        }

        .status-hadir {
            font-weight: bold;
            color: green;
        }

        .status-absen {
            font-weight: bold;
            color: red;
        }

        .signature-block {
            width: 300px;
            margin-top: 40px;
            margin-left: 60%;
            text-align: left;
            font-size: 12px;
        }

        .signature-block .kota-tanggal {
            margin-bottom: 80px;
        }

        .signature-block .nama {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <table class="kop-surat">
        <tr>
            <td class="judul-kop">
                <h1>PANITIA PELAKSANA SELEKSI</h1>
                <h2>(NAMA INSTANSI ANDA)</h2>
                <p>Tahun Pelajaran {{ $jadwal->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</p>
            </td>
        </tr>
    </table>

    <div class="judul-utama">
        <h2>LAPORAN KEHADIRAN PETUGAS (BY SISTEM)</h2>
        <p>{{ $jadwal->judul_kegiatan }}</p>
    </div>

    <table class="info-kegiatan">
        <tr>
            <td class="label">Lokasi</td>
            <td>: {{ $jadwal->lokasi_kegiatan }}</td>
        </tr>
        <tr>
            <td class="label">Waktu</td>
            <td>: {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai_pelaksanaan)->isoFormat('D MMMM YYYY') }}</td>
        </tr>
    </table>

    <strong>Rekapitulasi Kehadiran:</strong>
    <table class="rekap">
        <tr>
            <th>Total Terdaftar</th>
            <th>Total Hadir</th>
            <th>Total Tidak Hadir</th>
        </tr>
        <tr>
            <td style="text-align: center;">{{ $stats['total'] }}</td>
            <td style="text-align: center;">{{ $stats['hadir'] }}</td>
            <td style="text-align: center;">{{ $stats['tidak_hadir'] }}</td>
        </tr>
    </table>

    <table class="tabel-hadir">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Petugas</th>
                <th>Peran / Tugas</th>
                <th>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($petugas as $p)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $p->guru->nama_guru ?? 'N/A' }}</td>
                    <td>{{ $p->referensiTugas->deskripsi_tugas ?? 'N/A' }}</td>
                    <td style="text-align: center;">
                        @if ($p->absensi_admin)
                            <span class="status-hadir">HADIR</span>
                        @else
                            <span class="status-absen">TIDAK HADIR</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-block">
        <div class="kota-tanggal">
            {{ $jadwal->kota_surat ?? 'Kota' }},
            {{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->isoFormat('D MMMM YYYY') }}
        </div>
        <div>Panitia Pelaksana,</div>
        <div class="nama" style="margin-top: 80px;">
            {{ $jadwal->penandatangan->name ?? 'Nama Penandatangan' }}
        </div>
        <div>
            NIP. {{ $jadwal->penandatangan->nip ?? '... (NIP/ID Penandatangan)' }}
        </div>
    </div>
</body>

</html>
