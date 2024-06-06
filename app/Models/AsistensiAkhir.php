<?php

namespace App\Models;

use App\Models\Perkuliahan\AktivitasMahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsistensiAkhir extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function aktivitas()
    {
        return $this->belongsTo(AktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }
}
