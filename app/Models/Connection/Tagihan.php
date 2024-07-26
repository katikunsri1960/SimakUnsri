<?php

namespace App\Models\Connection;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tagihan extends Model
{
    protected $connection = 'keu_con'; // Koneksi keuangan
    protected $table = 'tagihan';

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_record_tagihan', 'id_record_tagihan');
    }

    public function periode()
    {
        return $this->belongsTo(Semester::class, 'kode_periode', 'id_semester');
    }
}
