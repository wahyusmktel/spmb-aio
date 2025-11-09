<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Berita Acara Pelaksanaan</title>
    <style>
        /* (CSS tidak berubah) */
        @page {
            margin: 40px 50px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.6;
        }

        .kop-surat {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
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

        .judul-surat {
            text-align: center;
            margin-bottom: 30px;
        }

        .judul-surat h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-decoration: underline;
        }

        .judul-surat p {
            font-size: 14px;
            margin: 5px 0 0 0;
        }

        .isi-ba p {
            text-align: justify;
            margin-bottom: 15px;
        }

        .info-kegiatan {
            width: 100%;
            margin: 15px 0;
        }

        .info-kegiatan td {
            padding: 3px 0;
        }

        .info-kegiatan td.label {
            width: 200px;
            /* Lebar label disesuaikan */
            vertical-align: top;
        }

        .info-kegiatan td.separator {
            width: 15px;
            vertical-align: top;
        }

        .tabel-saksi {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .tabel-saksi th,
        .tabel-saksi td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .tabel-saksi th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .tabel-saksi td.ttd {
            width: 150px;
        }

        .tabel-saksi td.nomor {
            width: 30px;
            text-align: center;
        }

        .signature-block {
            width: 300px;
            margin-top: 40px;
            margin-left: 60%;
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
        <h1>PANITIA PELAKSANA SELEKSI</h1>
        <p><strong>(NAMA INSTANSI ANDA)</strong></p>
        <p>Tahun Pelajaran {{ $jadwal->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</p>
    </div>

    <div class="judul-utama">
        <h2>BERITA ACARA PELAKSANAAN</h2>
        <p>Nomor: {{ $jadwal->nomor_surat_tugas }}/BA</p>
    </div>

    <div class="isi-ba">
        <p>
            Pada hari ini,
            <strong>{{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->isoFormat('dddd') }}</strong>
            tanggal <strong>{{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->isoFormat('D') }}</strong>
            bulan <strong>{{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->isoFormat('MMMM') }}</strong>
            tahun <strong>{{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->isoFormat('Y') }}</strong>,
            telah dilaksanakan kegiatan:
        </p>

        <table class="info-kegiatan">
            <tr>
                <td class="label">Nama Kegiatan</td>
                <td class="separator">:</td>
                <td><strong>{{ $jadwal->judul_kegiatan }}</strong></td>
            </tr>
            <tr>
                <td class="label">Lokasi</td>
                <td class="separator">:</td>
                <td>{{ $jadwal->lokasi_kegiatan }}</td>
            </tr>
            <tr>
                <td class="label">Waktu Pelaksanaan</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai_pelaksanaan)->isoFormat('D MMMM YYYY, H:mm') }} s/d
                    {{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->isoFormat('D MMMM YYYY, H:mm') }}</td>
            </tr>
            <tr>
                <td class="label">Jumlah Peserta Terdaftar</td>
                <td class="separator">:</td>
                <td><strong>{{ $statsPeserta['total'] }} orang</strong></td>
            </tr>
            <tr>
                <td class="label" style="padding-left: 15px;">&bull; Jumlah Peserta Hadir</td>
                <td class="separator">:</td>
                <td>{{ $statsPeserta['hadir'] }} orang</td>
            </tr>
            <tr>
                <td class="label" style="padding-left: 15px;">&bull; Jumlah Peserta Tidak Hadir</td>
                <td class="separator">:</td>
                <td>{{ $statsPeserta['tidak_hadir'] }} orang</td>
            </tr>
            <tr>
                <td class="label">Jumlah Petugas Terdaftar</td>
                <td class="separator">:</td>
                <td><strong>{{ $statsPetugas['total'] }} orang</strong></td>
            </tr>
            <tr>
                <td class="label" style="padding-left: 15px;">&bull; Jumlah Petugas Hadir</td>
                <td class="separator">:</td>
                <td>{{ $statsPetugas['hadir'] }} orang</td>
            </tr>
            <tr>
                <td class="label" style="padding-left: 15px;">&bull; Jumlah Petugas Tidak Hadir</td>
                <td class="separator">:</td>
                <td>{{ $statsPetugas['tidak_hadir'] }} orang</td>
            </tr>
        </table>
        <p>
            Pelaksanaan kegiatan seleksi berjalan dengan lancar dan tertib. Seluruh rangkaian acara telah dilaksanakan
            sesuai dengan prosedur yang berlaku.
        </p>
        <p>
            Demikian Berita Acara ini dibuat dengan sesungguhnya untuk dapat dipergunakan sebagaimana mestinya.
        </p>

        <p style="margin-top: 30px;">Petugas/Panitia yang Bertugas (Saksi):</p>
    </div>

    <table class="tabel-saksi">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Petugas</th>
                <th>Peran / Tugas</th>
                <th style="width: 200px;">Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($petugas as $p)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $p->guru->nama_guru ?? 'N/A' }}</td>
                    <td>{{ $p->referensiTugas->deskripsi_tugas ?? 'N/A' }}</td>
                    <td style="text-align: center; font-weight: bold;">
                        @if ($p->kehadiran)
                            <span style="color: green;">HADIR</span>
                        @else
                            <span style="color: red;">TIDAK HADIR</span>
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
        <div>Penanggung Jawab,</div>
        <div class="nama" style="margin-top: 80px;">
            {{ $jadwal->penandatangan->name ?? 'Nama Penandatangan' }}
        </div>
        <div>
            NIP. {{ $jadwal->penandatangan->nip ?? '... (NIP/ID Penandatangan)' }}
        </div>
    </div>

</body>

</html>
