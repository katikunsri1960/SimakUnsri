<?php

namespace App\Models\Perkuliahan;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function prasyarat_matkul()
    {
        return $this->hasMany(PrasyaratMatkul::class, 'id_matkul', 'id_matkul');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function rencana_pembelajaran()
    {
        return $this->hasMany(RencanaPembelajaran::class, 'id_matkul', 'id_matkul');
    }

    public function kelas_kuliah()
    {
        return $this->hasMany(KelasKuliah::class, 'id_matkul', 'id_matkul');
    }

    public function kurikulum()
    {
        return $this->hasOneThrough(
            ListKurikulum::class,
            MatkulKurikulum::class,
            'id_matkul', // Foreign key on MatkulKurikulum table...
            'id_kurikulum', // Foreign key on Kurikulum table...
            'id_matkul', // Local key on MataKuliah table...
            'id_kurikulum' // Local key on MatkulKurikulum table...
        );
    }

    public function matkul_prodi($id_prodi)
    {

        $kurikulum = ListKurikulum::where('id_prodi', $id_prodi)
                ->where('is_active', 1)
                ->pluck('id_kurikulum');

        $result = $this->with(['kurikulum'])->where('id_prodi', $id_prodi)
            ->whereHas('kurikulum', function($query) use ($kurikulum){
                $query->whereIn('list_kurikulums.id_kurikulum', $kurikulum);
            })
            ->orderBy('kode_mata_kuliah')
            ->get();

        // $result = $this->where('id_prodi', auth()->user()->fk_id)->orderBy('kode_mata_kuliah')->get();

        return $result;
    }

}
