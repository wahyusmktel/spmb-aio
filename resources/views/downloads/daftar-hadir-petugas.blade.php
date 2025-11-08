<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Petugas</title>
    <style>
        /* CSS WAJIB untuk DOMPDF */
        @page {
            margin: 40px;
            /* Margin halaman A4 */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
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

        .judul-utama p {
            font-size: 14px;
            margin: 5px 0 0 0;
        }

        /* INFO KEGIATAN */
        .info-kegiatan {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-kegiatan td {
            padding: 3px 0;
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
            padding: 8px;
            text-align: left;
        }

        .tabel-hadir th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .tabel-hadir td.ttd {
            width: 200px;
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
        <h2>DAFTAR HADIR PETUGAS</h2>
        <p>Nomor: {{ $jadwal->nomor_surat_tugas }}</p>
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
                <th>No.</th>
                <th>Nama Petugas</th>
                <th>NIP / ID</th>
                <th>Peran / Tugas</th>
                <th style="width: 150px;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($petugas as $p)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $p->guru->nama_guru ?? 'N/A' }}</td>
                    <td>{{ $p->guru->nip ?? 'N/A' }}</td>
                    <td>{{ $p->referensiTugas->deskripsi_tugas ?? 'N/A' }}</td>
                    <td class="ttd">
                        {{ $loop->iteration }}.
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-block">
        <div class="kota-tanggal">
            {{ $jadwal->kota_surat ?? 'Kota' }},
            {{ \Carbon\Carbon::parse($jadwal->tanggal_surat)->isoFormat('D MMMM YYYY') }}
        </div>
        <div class="nama">
            {{ $jadwal->penandatangan->name ?? 'Nama Penandatangan' }}
        </div>
        <div>
            NIP. {{ $jadwal->penandatangan->nip ?? '... (NIP/ID Penandatangan)' }}
        </div>
    </div>

</body>

</html>
