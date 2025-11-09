<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Laporan Petugas</title>
    <style>
        /* (CSS tidak berubah) */
        @page {
            margin: 40px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #000;
        }

        .kop-surat {
            text-align: center;
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .kop-surat p {
            font-size: 11px;
            margin: 0;
        }

        .judul-utama {
            text-align: center;
            margin-bottom: 10px;
        }

        .judul-utama h2 {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            text-decoration: underline;
        }

        .info-filter {
            font-size: 11px;
            margin-bottom: 15px;
        }

        .rekap {
            width: 40%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #000;
        }

        .rekap th,
        .rekap td {
            padding: 5px;
            border: 1px solid #000;
        }

        .rekap th {
            background-color: #f0f0f0;
        }

        .tabel-rekap {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .tabel-rekap th,
        .tabel-rekap td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .tabel-rekap th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .tabel-rekap td.nomor {
            width: 25px;
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
    </style>
</head>

<body>
    <div class="kop-surat">
        <h1>Rekapitulasi Laporan Petugas SELEKSI</h1>
        <p><strong>(NAMA INSTANSI ANDA)</strong></p>
    </div>

    <div class="judul-utama">
        <h2>LAPORAN PETUGAS</h2>
    </div>

    <p class="info-filter">
        <strong>Filter Laporan:</strong>
        @if (isset($jadwal_dipilih))
            Berdasarkan Kegiatan: <strong>{{ $jadwal_dipilih->judul_kegiatan }}</strong>
        @elseif ($tanggal_mulai && $tanggal_akhir)
            Berdasarkan Tanggal Kegiatan: <strong>{{ \Carbon\Carbon::parse($tanggal_mulai)->isoFormat('D MMM YYYY') }}
                s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->isoFormat('D MMM YYYY') }}</strong>
        @else
            <strong>Semua Data</strong>
        @endif
    </p>
    <strong>Rekapitulasi Total:</strong>
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

    <table class="tabel-rekap">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kegiatan</th>
                <th>Tgl Kegiatan</th>
                <th>Tahun Pelajaran</th>
                <th>Nama Petugas</th>
                <th>NIP</th>
                <th>Jabatan</th>
                <th>Peran Tugas</th>
                <th>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($petugas as $p)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $p->jadwalSeleksi->judul_kegiatan ?? 'N/A' }}</td>
                    <td>{{ $p->jadwalSeleksi->tanggal_mulai_pelaksanaan ? \Carbon\Carbon::parse($p->jadwalSeleksi->tanggal_mulai_pelaksanaan)->isoFormat('D MMM YYYY') : 'N/A' }}
                    </td>
                    <td>{{ $p->jadwalSeleksi->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</td>
                    <td>{{ $p->guru->nama_guru ?? 'N/A' }}</td>
                    <td>{{ $p->guru->nip ?? 'N/A' }}</td>
                    <td>{{ $p->guru->mata_pelajaran ?? 'N/A' }}</td>
                    <td>{{ $p->referensiTugas->deskripsi_tugas ?? 'N/A' }}</td>
                    <td style="text-align: center;">
                        @if ($p->kehadiran)
                            <span class="status-hadir">HADIR</span>
                        @else
                            <span class="status-absen">TIDAK HADIR</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
