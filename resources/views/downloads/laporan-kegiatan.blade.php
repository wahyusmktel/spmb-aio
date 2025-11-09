<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Kegiatan</title>
    <style>
        @page {
            margin: 40px 50px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.5;
        }

        .page-break {
            page-break-after: always;
        }

        /* KOP */
        .kop-surat {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .kop-surat h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .kop-surat p {
            font-size: 12px;
            margin: 0;
        }

        /* JUDUL */
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

        /* SECTION */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        /* TABEL INFO & REKAP */
        .info-table,
        .rekap-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .info-table td.label {
            width: 180px;
            font-weight: bold;
        }

        .info-table td.separator {
            width: 15px;
        }

        .rekap-table {
            width: 60%;
            border: 1px solid #000;
        }

        .rekap-table th,
        .rekap-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .rekap-table th {
            background-color: #f0f0f0;
        }

        /* TABEL DAFTAR */
        .daftar-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .daftar-table th,
        .daftar-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .daftar-table th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .daftar-table td.nomor {
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

        /* TTD */
        .signature-block {
            width: 300px;
            margin-top: 40px;
            margin-left: 60%;
            /* Posisi di kanan */
            text-align: left;
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

    <div class="kop-surat">
        <h1>LAPORAN KEGIATAN</h1>
        <p><strong>(NAMA INSTANSI ANDA)</strong></p>
        <p>Tahun Pelajaran {{ $jadwal->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</p>
    </div>

    <div class="section-title">A. Detail Kegiatan</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Kegiatan</td>
            <td class="separator">:</td>
            <td><strong>{{ $jadwal->judul_kegiatan }}</strong></td>
        </tr>
        <tr>
            <td class="label">Nomor Surat Tugas</td>
            <td class="separator">:</td>
            <td>{{ $jadwal->nomor_surat_tugas }}</td>
        </tr>
        <tr>
            <td class="label">Waktu Pelaksanaan</td>
            <td class="separator">:</td>
            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai_pelaksanaan)->isoFormat('D MMMM YYYY') }} s/d
                {{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->isoFormat('D MMMM YYYY') }}</td>
        </tr>
        <tr>
            <td class="label">Lokasi</td>
            <td class="separator">:</td>
            <td>{{ $jadwal->lokasi_kegiatan }}</td>
        </tr>
        <tr>
            <td class="label">Penanggung Jawab (TTD)</td>
            <td class="separator">:</td>
            <td>{{ $jadwal->penandatangan->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Status Eviden (Scan Manual)</td>
            <td class="separator">:</td>
            <td>
                @if ($jadwal->file_berita_acara && $jadwal->file_hadir_petugas && $jadwal->file_hadir_peserta)
                    <span style="color: green; font-weight: bold;">Lengkap Terunggah</span>
                @else
                    <span style="color: red; font-weight: bold;">Belum Lengkap</span>
                @endif
            </td>
        </tr>
    </table>

    <div class="section-title">B. Rekapitulasi Absensi (By Sistem)</div>
    <table class="rekap-table">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th>Total Terdaftar</th>
                <th>Total Hadir</th>
                <th>Total Tidak Hadir</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Peserta Seleksi</strong></td>
                <td style="text-align: center;">{{ $statsPeserta['total'] }}</td>
                <td style="text-align: center;">{{ $statsPeserta['hadir'] }}</td>
                <td style="text-align: center;">{{ $statsPeserta['tidak_hadir'] }}</td>
            </tr>
            <tr>
                <td><strong>Petugas Pelaksana</strong></td>
                <td style="text-align: center;">{{ $statsPetugas['total'] }}</td>
                <td style="text-align: center;">{{ $statsPetugas['hadir'] }}</td>
                <td style="text-align: center;">{{ $statsPetugas['tidak_hadir'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-block" style="margin-bottom: 30px;">
        <div class="kota-tanggal">
            {{ $jadwal->kota_surat ?? 'Kota' }},
            {{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->isoFormat('D MMMM YYYY') }}
        </div>
        <div>Penanggung Jawab,</div>
        <div class="nama" style="margin-top: 80px;">
            {{ $jadwal->penandatangan->name ?? 'Nama Penandatangan' }}
        </div>
        <div>
            NIP. {{ $jadwal->penandatangan->nip ?? '... (NIP/ID Penandatangan)' }}
        </div>
    </div>

    <div class="page-break"></div>

    <div class="section-title">Lampiran 1: Daftar Hadir Petugas</div>
    <table class="daftar-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Petugas</th>
                <th>Jabatan</th>
                <th>Peran Tugas</th>
                <th>Status Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($petugas as $p)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $p->guru->nama_guru ?? 'N/A' }}</td>
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

    <div class="section-title">Lampiran 2: Daftar Hadir Peserta</div>
    <table class="daftar-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Pendaftaran</th>
                <th>Nama Peserta</th>
                <th>Akun CBT</th>
                <th>Status Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peserta as $p)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $p->nomor_pendaftaran }}</td>
                    <td>{{ $p->nama_pendaftar }}</td>
                    <td>{{ $p->akunCbt->username ?? 'N/A' }}</td>
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
