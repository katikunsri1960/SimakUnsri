<?php

namespace App\Models\Perkuliahan;

use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaAktivitasMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function aktivitas_mahasiswa()
    {
        return $this->belongsTo(AktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function getKrsAkt($id_reg, $id_semester)
    {
        //DATA AKTIVITAS
        
        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

        $db = new MataKuliah();

        $data_akt = $db->getMKAktivitas($riwayat_pendidikan->id_prodi, $riwayat_pendidikan->id_kurikulum);

        

        if( $data_akt == NULL)
        {
            $mk_akt=NULL;
            $data_akt_ids = NULL;

        }
        else
        {
            $mk_akt = $data_akt['data']['data'];
            $data_akt_ids = array_column($mk_akt, 'id_matkul');
        }
        // dd($data_akt);

        // AKTIVITAS MAHASISWA YG DIAMBIL
        $krs_akt = $this::with(['aktivitas_mahasiswa','aktivitas_mahasiswa.bimbing_mahasiswa', 'aktivitas_mahasiswa.konversi'])
                            ->whereHas('aktivitas_mahasiswa' , function($query) use($id_semester, $riwayat_pendidikan) {
                                $query->where('id_semester', $id_semester)
                                ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                                ->whereIn('id_jenis_aktivitas', ['1','2', '3', '4','6','15', '22']);
                            })
                            ->whereHas('aktivitas_mahasiswa.bimbing_mahasiswa' , function($query) {
                                $query->whereNot('id_bimbing_mahasiswa', NUll);
                            })
                            ->where('id_registrasi_mahasiswa', $id_reg)
                            ->get();

            return [$krs_akt, $data_akt_ids, $mk_akt];
    }
}
