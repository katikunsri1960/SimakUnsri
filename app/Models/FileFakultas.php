<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileFakultas extends Model
{
    use HasFactory;

    protected $table = 'file_fakultas';
    protected $fillable = [
        'fakultas_id',
        'nama_file',
        'dir_file',
        'tgl_surat',
        'tgl_kegiatan',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

}
