<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SKPISubBidangKegiatan extends Model
{
    use HasFactory;

    protected $table = 'skpi_sub_bidang_kegiatan';
    protected $fillable = ['bidang_id', 'nama_sub_bidang'];

    public function bidang()
    {
        return $this->belongsTo(SKPIBidangKegiatan::class, 'bidang_id');
    }

    public function jenisKegiatan()
    {
        return $this->hasMany(SKPIJenisKegiatan::class, 'sub_bidang_id');
    }
}