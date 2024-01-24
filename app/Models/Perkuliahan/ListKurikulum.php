<?php

namespace App\Models\Perkuliahan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListKurikulum extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function matkul_kurikulum()
    {
        return $this->hasMany(MatkulKurikulum::class, 'id_kurikulum', 'id_kurikulum');
    }

}
