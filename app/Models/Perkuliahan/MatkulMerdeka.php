<?php

namespace App\Models\Perkuliahan;

use App\Models\ProgramStudi;
use Illuminate\Support\Facades\DB;
use App\Models\Perkuliahan\MataKuliah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MatkulMerdeka extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function matkul()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id_matkul');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }



    // public function getMKMerdeka($semester_select, $selectedProdiId)
    // {
    //     $mk_merdek = $this::with('matkul.rencana_pembelajaran')
    //             ->leftJoin('mata_kuliahs', 'matkul_merdekas.id_matkul', '=', 'mata_kuliahs.id_matkul')
    //             ->leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
    //             ->select(
    //                 // '*'
    //                 'mata_kuliahs.id_prodi','mata_kuliahs.id_matkul', 'mata_kuliahs.kode_mata_kuliah', 'mata_kuliahs.nama_mata_kuliah', 'matkul_kurikulums.semester', 'matkul_kurikulums.sks_mata_kuliah'
    //                 )
    //             ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_select."') AS jumlah_kelas_kuliah"))
    //             // ->whereIn('mata_kuliahs.id_prodi', [$selectedProdiId]) // Hanya mengambil mata kuliah yang termasuk dalam program studi yang dipilih
    //             ->orderBy('jumlah_kelas_kuliah', 'DESC')
    //             ->orderBy('matkul_kurikulums.semester', 'ASC')
    //             ->orderBy('matkul_kurikulums.sks_mata_kuliah', 'ASC')
    //             ->get();
                

    //     $mk_merdeka = $this ->with(['matkul', 'matkul.kelas-kuliah', 'matkul.rencana_pembelajaran'])
    //                         ->withCount(['kelas_kuliah as jumlah_kelas' => function ($q) use($semester_select){
    //                             $q->where('id_semester', $semester_select);
    //                         },
    //                         'rencana_pembelajaran as jumlah_rps' => function ($q) {
    //                             $q->where('approved', 0);
    //                         }])
    //                         // ->where('id_prodi', $selectedProdiId)
    //                         ->orderBy('jumlah_rps', 'ASC')
    //                         ->get();
    //                         dd($mk_merdeka);

    //     return $mk_merdeka ;
    // }
}
