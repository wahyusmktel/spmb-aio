<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Perintah Tugas</title>
    <style>
        @page {
            margin: 40px 50px;
            /* Margin halaman A4 */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.6;
        }

        /* KOP (Placeholder) */
        .kop-surat {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 5px;
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

        /* JUDUL SURAT */
        .judul-surat {
            text-align: center;
            margin-top: 20px;
        }

        .judul-surat h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-decoration: underline;
        }

        .judul-surat p {
            font-size: 12px;
            margin: 0;
        }

        /* ISI SURAT */
        .isi-surat {
            margin-top: 30px;
        }

        .isi-surat p {
            text-align: justify;
            margin-bottom: 15px;
        }

        /* TABEL INFO */
        .info-table {
            margin-left: 30px;
            /* Indentasi */
            width: 100%;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .info-table td.label {
            width: 120px;
            /* Lebar label */
        }

        .info-table td.separator {
            width: 15px;
        }

        /* TABEL PETUGAS */
        .tabel-petugas {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .tabel-petugas th,
        .tabel-petugas td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .tabel-petugas th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .tabel-petugas td.nomor {
            width: 30px;
            text-align: center;
        }

        /* TANDA TANGAN */
        .signature-block {
            width: 300px;
            margin-top: 40px;
            margin-left: 60%;
            /* Posisi di kanan */
            text-align: left;
        }

        .signature-block .kota-tanggal {
            margin-bottom: 5px;
        }

        .signature-block .jabatan-ttd {
            margin-bottom: 80px;
            /* Jarak untuk TTD */
        }

        .signature-block .nama-ttd {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="kop-surat">
        <h1>YAYASAN PENDIDIKAN TELKOM</h1>
        <p><strong>SMK TELKOM LAMPUNG</strong></p>
        <p>Jl. Jend. A. Yani No. 10, Kota Anda, Lampung</p>
    </div>

    <div class="judul-surat">
        <h2>SURAT PERINTAH TUGAS</h2>
        <p>Nomor: {{ $jadwal->nomor_surat_tugas }}</p>
    </div>

    <div class="isi-surat">
        <p>Yang bertanda tangan di bawah ini Kepala SMK Telkom Lampung:</p>

        <table class="info-table" style="margin-bottom: 15px;">
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td>{{ $jadwal->penandatangan->name ?? '(Nama Penandatangan)' }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td class="separator">:</td>
                <td>(.......................)</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="separator">:</td>
                <td>Kepala SMK Telkom Lampung</td>
            </tr>
        </table>

        <p>
            Dengan ini menugaskan kepada nama-nama di bawah ini untuk melaksanakan tugas piket SPMB (Seleksi Penerimaan
            Murid Baru)
            Tahun Pelajaran <strong>{{ $jadwal->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</strong>
            SMK Telkom Lampung pada:
        </p>

        <table class="info-table">
            <tr>
                <td class="label">Hari / Tanggal</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai_pelaksanaan)->isoFormat('dddd, D MMMM YYYY') }}</td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai_pelaksanaan)->format('H:i') }} s/d
                    {{ \Carbon\Carbon::parse($jadwal->tanggal_akhir_pelaksanaan)->format('H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="label">Tempat</td>
                <td class="separator">:</td>
                <td>{{ $jadwal->lokasi_kegiatan }}</td>
            </tr>
        </table>

        <p style="margin-top: 15px;">Adapun nama-nama anggota yang bertugas sebagai berikut:</p>
    </div>

    <table class="tabel-petugas">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Tugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($petugas as $p)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $p->guru->nama_guru ?? 'N/A' }}</td>
                    <td>{{ $p->guru->mata_pelajaran ?? 'N/A' }}</td>
                    <td>{{ $p->referensiTugas->deskripsi_tugas ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>
        Demikian surat perintah tugas ini dibuat dengan sebenar-benarnya untuk dijalankan dengan penuh tanggung jawab
        dan dapat dipergunakan sebagaimana mestinya.
    </p>

    <div class="signature-block">
        <div class="kota-tanggal">
            {{ $jadwal->kota_surat ?? 'Kota' }},
            {{ \Carbon\Carbon::parse($jadwal->tanggal_surat)->isoFormat('D MMMM YYYY') }}
        </div>
        <div class="jabatan-ttd">
            Kepala SMK Telkom Lampung,
        </div>
        <div class="nama-ttd">
            {{ $jadwal->penandatangan->name ?? '(Nama Penandatangan)' }}
        </div>
        <div>
            NIP. (.......................)
        </div>
    </div>

</body>

</html>
