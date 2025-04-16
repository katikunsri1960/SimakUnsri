<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalurMasuk extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function missingJalurMasuk()
    {
        $data = [
            [
                'id_jalur_masuk' => 1,
                'nama_jalur_masuk' => 'SBMPTN',
            ],
            [
                'id_jalur_masuk' => 2,
                'nama_jalur_masuk' => 'SNMPTN',
            ],
            [
                'id_jalur_masuk' => 5,
                'nama_jalur_masuk' => 'Seleksi Mandiri PTN',
            ],
            [
                'id_jalur_masuk' => 6,
                'nama_jalur_masuk' => 'Seleksi Mandiri PTS',
            ],
            [
                'id_jalur_masuk' => 7,
                'nama_jalur_masuk' => 'Ujian Masuk Bersama PTN (UMB-PT)',
            ],
            [
                'id_jalur_masuk' => 8,
                'nama_jalur_masuk' => 'Ujian Masuk Bersama PTS (UMB-PTS)',
            ],
        ];
    }
}
