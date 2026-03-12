<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SKPIBidangKegiatan extends Model
{
    protected $table = 'skpi_bidang_kegiatan';
    protected $guarded = ['id'];

    public function jenis()
    {
        return $this->hasMany(SkpiJenisKegiatan::class, 'bidang_id');
    }
}