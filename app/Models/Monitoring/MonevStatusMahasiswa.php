<?php

namespace App\Models\Monitoring;

use App\Models\ProgramStudi;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonevStatusMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function details()
    {
        return $this->hasMany(MonevStatusMahasiswaDetail::class);
    }
}
