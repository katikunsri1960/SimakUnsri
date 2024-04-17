<?php

namespace App\Models\Perkuliahan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PrasyaratMatkul extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function matkul_prasyarat()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul_prasyarat', 'id_matkul');
    }

    public function prasyarat_store($id_matkul, $prasyarat)
    {
        DB::beginTransaction();

        try {

            if ($prasyarat) {
                $now = now();
                $data = array_map(function ($p) use ($id_matkul, $now) {
                    return [
                        'id_matkul' => $id_matkul,
                        'id_matkul_prasyarat' => $p,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }, $prasyarat);

                $this->insert($data);
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            $result = [
                'status' => 'error',
                'message' => "Terdapat mata kuliah prasyarat yang sama!!"
            ];
            return $result;
        }

        $result = [
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ];

        return $result;
    }

}
