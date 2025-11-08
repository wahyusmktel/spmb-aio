<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Peserta</title>
    <style>
        /* CSS WAJIB untuk DOMPDF */
        @page {
            margin: 40px;
            /* Margin halaman A4 */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            /* Sedikit lebih kecil agar muat */
            color: #000;
        }

        /* KOP SURAT */
        .kop-surat {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat td {
            vertical-align: top;
        }

        .kop-surat .logo {
            width: 80px;
        }

        .kop-surat .logo img {
            width: 100%;
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

        /* JUDUL UTAMA */
        .judul-utama {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-utama h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-decoration: underline;
        }

        /* INFO KEGIATAN */
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
            /* Lebar label */
        }

        /* TABEL DAFTAR HADIR */
        .tabel-hadir {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .tabel-hadir th,
        .tabel-hadir td {
            border: 1px solid #000;
            padding: 6px;
            /* Padding lebih kecil */
            text-align: left;
        }

        .tabel-hadir th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .tabel-hadir td.ttd {
            width: 150px;
            /* Lebar kolom TTD */
        }

        .tabel-hadir td.nomor {
            width: 30px;
            text-align: center;
        }

        /* TANDA TANGAN PENANGGUNG JAWAB */
        .signature-block {
            width: 300px;
            margin-top: 40px;
            margin-left: 60%;
            /* Posisi di kanan */
            text-align: left;
            font-size: 12px;
        }

        .signature-block .kota-tanggal {
            margin-bottom: 80px;
            /* Jarak untuk TTD */
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
                <p>Alamat: Jl. Instansi No. 123, Kota Anda - Email: info@instansi.com</p>
            </td>
        </tr>
    </table>

    <div class="judul-utama">
        <h2>DAFTAR HADIR PESERTA</h2>
    </div>

    <table class="info-kegiatan">
        <tr>
            <td class="label">Nama Kegiatan</td>
            <td>: {{ $jadwal->judul_kegiatan }}</td>
        </tr>
        <tr>
            <td class="label">Lokasi</td>
            <td>: {{ $jadwal->lokasi_kegiatan }}</td>
        </tr>
        <tr>
            <td class="label">Waktu Pelaksanaan</td>
            <td>: {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai_pelaksanaan)->isoFormat('D MMMM YYYY') }} s/d
                {{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->isoFormat('D MMMM YYYY') }}</td>
        </tr>
    </table>

    <table class="tabel-hadir">
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Nomor Pendaftaran</th>
                <th rowspan="2">Nama Peserta</th>
                <th colspan="2">Tanda Tangan</th>
            </tr>
            <tr>
                <th>Sesi 1</th>
                <th>Sesi 2</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peserta as $p)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $p->nomor_pendaftaran ?? 'N/A' }}</td>
                    <td>{{ $p->nama_pendaftar ?? 'N/A' }}</td>
                    <td class="ttd">
                        @if ($loop->odd)
                            {{ $loop->iteration }}.
                        @endif
                    </td>
                    <td class="ttd">
                        @if ($loop->even)
                            {{ $loop->iteration }}.
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-block">
        <div class="kota-tanggal">
            {{ $jadwal->kota_surat ?? 'Kota' }}, {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}
        </div>
        <div>Panitia Pelaksana,</div>
        <div class="nama" style="margin-top: 80px;">
            (.........................................)
        </div>
        <div>
            NIP. .....................................
        </div>
    </div>

</body>

</html>
