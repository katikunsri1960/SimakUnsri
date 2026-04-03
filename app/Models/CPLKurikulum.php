<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Perkuliahan\ListKurikulum;

class CPLKurikulum extends Model
{
    protected $table = 'cpl_kurikulums';

    protected $guarded = [];

    // relasi ke kurikulum (optional)
    public function kurikulum()
    {
        return $this->belongsTo(ListKurikulum::class, 'id_kurikulum', 'id_kurikulum');
    }
}
