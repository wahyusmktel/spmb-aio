<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kartu Peserta</title>
    <style>
        /* CSS WAJIB untuk DOMPDF */
        @page {
            margin: 20px;
            /* Margin halaman A4 */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
        }

        .page-break {
            page-break-after: always;
        }

        /* Layout Grid Kartu */
        .kartu-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            /* Ganti 'space-between' agar rata kiri-kanan */
            width: 100%;
        }

        /* Styling Kartu (2 kolom, 5 baris = 10 kartu) */
        .kartu {
            width: 48%;
            /* Sekitar 48% untuk 2 kolom dengan sedikit jarak */
            height: 180px;
            /* Sesuaikan tinggi kartu */
            border: 1px solid #000;
            border-radius: 8px;
            padding: 10px;
            margin: 1%;
            /* Jarak antar kartu */
            box-sizing: border-box;
            /* PENTING agar padding tidak merusak layout */
            overflow: hidden;
            position: relative;
            /* Untuk footer */
        }

        .kartu-header {
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-align: center;
        }

        .kartu-header h4 {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
        }

        .kartu-header p {
            margin: 0;
            font-size: 10px;
        }

        .kartu-body table {
            width: 100%;
            border-collapse: collapse;
        }

        .kartu-body td {
            padding: 4px;
            vertical-align: top;
        }

        .kartu-body td:first-child {
            width: 90px;
            /* Lebar label */
            font-weight: bold;
        }

        .kartu-footer {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 8px;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="kartu-container">
        @foreach ($peserta as $p)
            <div class="kartu">
                <div class="kartu-header">
                    <h4>KARTU PESERTA SELEKSI</h4>
                    <p>TAHUN PELAJARAN {{ $p->jadwalSeleksi->tahunPelajaran->nama_tahun_pelajaran ?? 'N/A' }}</p>
                </div>
                <div class="kartu-body">
                    <table>
                        <tr>
                            <td>Kegiatan</td>
                            <td>: {{ $p->jadwalSeleksi->judul_kegiatan }}</td>
                        </tr>
                        <tr>
                            <td>No. Pendaftaran</td>
                            <td>: {{ $p->nomor_pendaftaran }}</td>
                        </tr>
                        <tr>
                            <td>Nama Peserta</td>
                            <td>: {{ $p->nama_pendaftar }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 10px; border-top: 1px dashed #999;">Login Aplikasi CBT:
                            </td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td>: <strong>{{ $p->akunCbt->username ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td>: <strong>{{ $p->akunCbt->password ?? 'N/A' }}</strong></td>
                        </tr>
                    </table>
                </div>
                <div class="kartu-footer">
                    Harap disimpan baik-baik.
                </div>
            </div>

            @if ($loop->iteration % 10 == 0 && !$loop->last)
    </div>
    <div class="page-break"></div>
    <div class="kartu-container">
        @endif
        @endforeach
    </div>

</body>

</html>
