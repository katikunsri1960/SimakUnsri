<?php

namespace App\Models\Perkuliahan;

use App\Services\Feeder\FeederAct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranskripMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function delete_transkrip($id)
    {
        // Ambil data transkrip berdasarkan id
        $transkrip = $this->find($id);



        if (!$transkrip) {
            return ['status' => 0, 'message' => 'Data tidak ditemukan'];
        }
        $act = 'DeleteTranskripMahasiswa';

        $key = [
            'id_registrasi_mahasiswa' => $transkrip->id_registrasi_mahasiswa,
            'id_matkul' => $transkrip->id_matkul
        ];


        $service = new FeederAct($act, $key);

        $response = $service->runWS();

        if (isset($response['error_code']) && $response['error_code'] != 0) {

            return ['status' => 0, 'message' => 'Gagal menghapus data di Feeder: ' . $response['error_desc']];

        }

        // Hapus data transkrip
        $transkrip->delete();

        return ['status' => 1, 'message' => 'Data berhasil dihapus'];
    }

}
