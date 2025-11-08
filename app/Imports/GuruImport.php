<?php

namespace App\Imports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // PENTING: Untuk membaca header
use Maatwebsite\Excel\Concerns\WithValidation; // PENTING: Untuk validasi data
use Maatwebsite\Excel\Concerns\SkipsOnFailure; // Opsional: Skip jika gagal
use Maatwebsite\Excel\Validators\Failure;      // Opsional: Untuk menangkap error
use Maatwebsite\Excel\Concerns\SkipsFailures;  // Opsional: Untuk menangkap error

class GuruImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // $row['nip'] akan sama dengan nama header di file Excel
        return new Guru([
            'nip'             => $row['nip'],
            'nama_guru'       => $row['nama_guru'],
            'mata_pelajaran'  => $row['mata_pelajaran'],
        ]);
    }

    /**
     * Tentukan aturan validasi untuk setiap baris.
     */
    public function rules(): array
    {
        return [
            // 'nip' harus unik di tabel 'gurus'
            'nip' => 'required|string|max:20|unique:gurus,nip',

            // 'nama_guru' harus diisi
            'nama_guru' => 'required|string|max:255',

            // 'mata_pelajaran' harus diisi
            'mata_pelajaran' => 'required|string|max:100',
        ];
    }
}
