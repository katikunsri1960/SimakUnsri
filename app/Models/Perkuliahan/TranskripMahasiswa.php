<?php

namespace App\Models\Perkuliahan;

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

        if (! $transkrip) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        // Hapus data transkrip
        // $transkrip->delete();

        return ['status' => 'success', 'message' => 'Data berhasil dihapus'];
    }
}
