<?php

namespace App\Imports;

use App\Models\ReferensiAkunCbt;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\Rule; // <-- PENTING

class ReferensiAkunCbtImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    // Variabel untuk menyimpan ID Tahun Pelajaran Aktif
    private $id_tahun_pelajaran;

    /**
     * Kita wajibkan class ini menerima ID Tahun Aktif
     */
    public function __construct(int $id_tahun_pelajaran)
    {
        $this->id_tahun_pelajaran = $id_tahun_pelajaran;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // $row['username'], $row['password']
        // Model mutator akan otomatis HASH password-nya
        return new ReferensiAkunCbt([
            'id_tahun_pelajaran' => $this->id_tahun_pelajaran,
            'username'           => $row['username'],
            'password'           => $row['password'],
            'status'             => $row['status'] ?? false, // Default false jika kolom 'status' tidak ada
        ]);
    }

    /**
     * Validasi untuk setiap baris di Excel
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                // Validasi 'unique' HANYA pada tabel 'referensi_akun_cbts'
                // DAN HANYA untuk 'id_tahun_pelajaran' yang aktif
                Rule::unique('referensi_akun_cbts')->where('id_tahun_pelajaran', $this->id_tahun_pelajaran)
            ],
            'password' => ['required', 'string', 'min:8'],
            'status'   => ['nullable', 'boolean'], // Kolom status opsional, tapi jika ada harus 0/1
        ];
    }
}
