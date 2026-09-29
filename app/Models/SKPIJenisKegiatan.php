<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SKPIJenisKegiatan extends Model
{
    protected $table = 'skpi_jenis_kegiatan';
    protected $guarded = ['id'];

    public function bidang()
    {
        return $this->belongsTo(SKPIBidangKegiatan::class, 'bidang_id');
    }

    public function subBidang()
    {
        return $this->belongsTo(SKPISubBidangKegiatan::class, 'sub_bidang_id');
    }
}
