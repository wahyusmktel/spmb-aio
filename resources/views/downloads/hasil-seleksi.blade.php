<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Seleksi</title>
    <style>
        /* (CSS sama seperti daftar hadir, kita pakai ulang) */
        @page {
            margin: 40px 50px;
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
        }

        /* TABEL HASIL */
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

        /* TTD */
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
                <h1>LAPORAN HASIL SELEKSI</h1>
                <h2>(NAMA INSTANSI ANDA)</h2>
                <p>Tahun Pelajaran {{ $jadwal->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</p>
            </td>
        </tr>
    </table>

    <div class="judul-utama">
        <h2>HASIL SELEKSI TES BUTA WARNA</h2>
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
                <th>Nilai (Skala 100)</th>
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
                        @if ($p->status_tes_buta_warna)
                            <span style="color: green; font-weight: bold;">Selesai</span>
                        @else
                            <span style="color: red;">Belum Mengerjakan</span>
                        @endif
                    </td>
                    <td class="nilai">
                        {{ $p->nilai_tes_buta_warna ?? '-' }}
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
