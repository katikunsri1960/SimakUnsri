<?php

namespace App\Models\Mahasiswa;

use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Registrasi;
use App\Models\Connection\Tagihan;
use App\Models\Dosen\BiodataDosen;
use App\Models\Semester;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\JalurMasuk;
use App\Models\PenundaanBayar;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Mahasiswa\LulusDo;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class RiwayatPendidikan extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['angkatan', 'gelombang_masuk', 'id_tanggal_daftar'];

    public function getIdTanggalDaftarAttribute()
    {
        return $this->tanggal_daftar ? Carbon::parse($this->tanggal_daftar)->format('d-m-Y') : '';
    }

    public function anggota_aktivitas_mahasiswa()
    {
        return $this->hasMany(AnggotaAktivitasMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function aktivitas_mahasiswa()
    {
        return $this->hasManyThrough(AktivitasMahasiswa::class, AnggotaAktivitasMahasiswa::class, 'id_registrasi_mahasiswa', 'id_aktivitas', 'id_registrasi_mahasiswa', 'id_aktivitas');
    }

    public function dosen_pa()
    {
        return $this->belongsTo(BiodataDosen::class, 'dosen_pa', 'id_dosen');
    }

    public function peserta_kelas()
    {
        return $this->hasMany(PesertaKelasKuliah::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function lulus_do()
    {
        return $this->belongsTo(LulusDo::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function pembimbing_akademik()
    {
        return $this->belongsTo(BiodataDosen::class, 'dosen_pa', 'id_dosen');
    }

    public function kurikulum()
    {
        return $this->belongsTo(ListKurikulum::class, 'id_kurikulum', 'id_kurikulum');
    }

    public function biodata()
    {
        return $this->belongsTo(BiodataMahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function periode_masuk()
    {
        return $this->belongsTo(Semester::class, 'id_periode_masuk', 'id_semester');
    }

    public function getAngkatanAttribute()
    {
        return substr($this->id_periode_masuk, 0, 4);
    }

    public function getGelombangMasukAttribute()
    {
        return substr($this->id_periode_masuk, 4, 1);
    }

    public function jalur_masuk()
    {
        return $this->belongsTo(JalurMasuk::class, 'id_jalur_daftar', 'id_jalur_masuk');
    }

    public function aktivitas_kuliah()
    {
        return $this->hasMany(AktivitasKuliahMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function mahasiswaProdi($id_prodi)
    {
        return $this->where('id_prodi', $id_prodi);
    }

    public function beasiswa()
    {
        return $this->hasOne(BeasiswaMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function transkrip_mahasiswa()
    {
        return $this->hasMany(TranskripMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function set_kurikulum_angkatan($tahun_angkatan, $id_kurikulum, $prodi)
    {
        try {
            DB::beginTransaction();
            $this->where(DB::raw('LEFT(id_periode_masuk, 4)'), $tahun_angkatan)->where('id_prodi', $prodi)
                ->update(['id_kurikulum' => $id_kurikulum]);

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Kurikulum berhasil diubah'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 'error',
                'message' => 'Kurikulum gagal diubah'
            ];

            return $result;
        }

        return $result;
    }

    public function detail_isi_krs($id_prodi, $semesterAktif)
    {
        $data = $this->with('pembimbing_akademik')->where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->where(function ($query) use ($semesterAktif) {
                    $query->whereExists(function ($subquery) use ($semesterAktif) {
                        $subquery->select(DB::raw(1))
                            ->from('peserta_kelas_kuliahs as p')
                            ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                            ->where('k.id_semester', $semesterAktif)
                            ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                    })
                    ->orWhere(function ($query) use ($semesterAktif) {
                        $query->whereNotExists(function ($subquery) use ($semesterAktif) {
                            $subquery->select(DB::raw(1))
                                ->from('peserta_kelas_kuliahs as p')
                                ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                                ->where('k.id_semester', $semesterAktif)
                                ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        })
                        ->whereExists(function ($subquery) use ($semesterAktif) {
                            $subquery->select(DB::raw(1))
                                ->from('anggota_aktivitas_mahasiswas as aam')
                                ->join('aktivitas_mahasiswas as a', 'aam.id_aktivitas', '=', 'a.id_aktivitas')
                                ->where('a.id_semester', $semesterAktif)
                                ->whereIn('a.id_jenis_aktivitas', [1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22])
                                ->whereColumn('aam.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        });
                    });
                })
                ->distinct()
                ->get();

        return $data;
    }

    public function krs_data($id_prodi, $semesterAktif, $isApproved)
    {
        $data = $this->with(['beasiswa'])->where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->where(function ($query) use ($semesterAktif, $isApproved) {
                    $query->whereExists(function ($subquery) use ($semesterAktif, $isApproved) {
                        $subquery->select(DB::raw(1))
                            ->from('peserta_kelas_kuliahs as p')
                            ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                            ->where('k.id_semester', $semesterAktif)
                            ->where('p.approved', $isApproved)
                            ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                    })
                    ->orWhere(function ($query) use ($semesterAktif, $isApproved) {
                        $query->whereNotExists(function ($subquery) use ($semesterAktif, $isApproved) {
                            $subquery->select(DB::raw(1))
                                ->from('peserta_kelas_kuliahs as p')
                                ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                                ->where('k.id_semester', $semesterAktif)
                                ->where('p.approved', $isApproved)
                                ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        })
                        ->whereExists(function ($subquery) use ($semesterAktif, $isApproved) {
                            $subquery->select(DB::raw(1))
                                ->from('anggota_aktivitas_mahasiswas as aam')
                                ->join('aktivitas_mahasiswas as a', 'aam.id_aktivitas', '=', 'a.id_aktivitas')
                                ->where('a.id_semester', $semesterAktif)
                                ->where('a.approve_krs', $isApproved)
                                ->whereIn('a.id_jenis_aktivitas', [1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22])
                                ->whereColumn('aam.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        });
                    });
                })
                ->distinct()
                ->get();

            // foreach($data as $key => $value) {
            //     $value->rm_no_test = Registrasi::where('rm_nim', $value->nim)->pluck('rm_no_test')->first();
            // }
                // $id_test = Registrasi::where('rm_nim', $user->username)->pluck('rm_no_test')->first();

                // // dd($beasiswa);

                // $tagihan = Tagihan::with('pembayaran')
                //         ->whereIn('tagihan.nomor_pembayaran', [$id_test, $nim])
                //         ->where('tagihan.kode_periode', $semester_aktif->id_semester)
                //         ->first();

                // if ($tagihan) {
                //     $tagihan->waktu_berakhir = Carbon::parse($tagihan->waktu_berakhir)->translatedFormat('d F Y');
                // }

                // $pembayaran = Tagihan::with('pembayaran')
                //     ->whereIn('nomor_pembayaran', [$id_test, $nim])
                //     ->orderBy('kode_periode', 'ASC')
                //     ->get();

                // foreach ($pembayaran as $item) {
                //     if ($item->pembayaran) {
                //         $item->pembayaran->waktu_transaksi = Carbon::parse($item->pembayaran->waktu_transaksi)->translatedFormat('d F Y');
                //     }
                // }
            //  dd($data);
        return $data;
    }

    public function tidak_isi_krs($id_prodi, $semesterAktif)
    {
        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));

        $data = $this->with(['pembimbing_akademik', 'beasiswa', ])->where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                ->where(function ($query) use ($semesterAktif) {
                    $query->whereNotExists(function ($subquery) use ($semesterAktif) {
                        $subquery->select(DB::raw(1))
                            ->from('peserta_kelas_kuliahs as p')
                            ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                            ->where('k.id_semester', $semesterAktif)
                            ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                    })
                    ->where(function ($query) use ($semesterAktif) {
                        $query->whereNotExists(function ($subquery) use ($semesterAktif) {
                            $subquery->select(DB::raw(1))
                                ->from('anggota_aktivitas_mahasiswas as aam')
                                ->join('aktivitas_mahasiswas as a', 'aam.id_aktivitas', '=', 'a.id_aktivitas')
                                ->where('a.id_semester', $semesterAktif)
                                ->whereIn('a.id_jenis_aktivitas', [1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22])
                                ->whereColumn('aam.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        });
                    });
                })
                ->distinct()
                ->get();

        foreach($data as $key => $value) {
            $value->rm_no_test = Registrasi::where('rm_nim', $value->nim)->pluck('rm_no_test')->first();

            $value->tagihan = Tagihan::with('pembayaran')
                        ->whereIn('tagihan.nomor_pembayaran', [$value->rm_no_test, $value->nim])
                        ->where('tagihan.kode_periode', $semesterAktif)
                        ->first();

            // if ($value->tagihan) {
            // dd($value->tagihan);
            // }

            $value->penundaan_bayar = PenundaanBayar::where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)
                                    ->where('id_semester', $semesterAktif)
                                    ->first() ? 1 : 0;
        }
        // dd($data);
        return $data;
    }

    public function nilai_transfer_pendidikan($id_prodi, $semester)
    {
        $data = $this->where('id_prodi', $id_prodi)
                    ->where('id_periode_masuk', $semester)
                    ->whereNull('id_jenis_keluar')
                    ->whereIn('id_jenis_daftar', [2,16])
                    ->get();

        return $data;
    }


}
