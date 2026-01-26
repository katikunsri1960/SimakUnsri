<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perpus\BebasPustaka;
use App\Models\ProgramStudi;
use App\Models\BkuProgramStudi;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Referensi\PredikatKelulusan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wisuda extends Model
{
    use HasFactory;
    protected $table = 'data_wisuda';
    protected $appends = ['id_tanggal_sk_yudisium', 'approved_text'];

    // App\Models\Wisuda.php
    protected $fillable = [
        'id_perguruan_tinggi',
        'id_registrasi_mahasiswa',
        'id_prodi',
        'id_file_fakultas',
        'id_gelar_lulusan',
        'tgl_masuk',
        'tgl_keluar',
        'lama_studi',
        'no_peserta_ujian',
        'sks_diakui',
        'ipk',
        'no_ijazah',
        'wisuda_ke',
        'no_sk_yudisium',
        'tgl_sk_yudisium',
        'sk_yudisium_file',
        'id_aktivitas',
        'keterangan',
        'angkatan',
        'nim',
        'nama_mahasiswa',
        'alamat_orang_tua',
        'kosentrasi',
        'pas_foto',
        'lokasi_kuliah',
        'tgl_sk_pembimbing',
        'no_sk_pembimbing',
        'judul_eng',
        'abstrak_ta',
        'abstrak_file',
        'abstrak_file_eng',
        'ijazah_terakhir_file',
        'id_bku_prodi',
        'id_predikat_kelulusan',
        'approved',
        'alasan_pembatalan',
        'no_urut',
        // KOLOM SEMENTARA
        'bebas_pustaka',
        'useptData',


    ];

    public function file_fakultas()
    {
        return $this->belongsTo(FileFakultas::class, 'id_file_fakultas', 'id');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function riwayat_pendidikan()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function aktivitas_kuliah()
    {
        return $this->hasMany(AktivitasKuliahMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function aktivitas_mahasiswa()
    {
        return $this->belongsTo(AktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function transkrip_mahasiswa()
    {
        return $this->hasMany(TranskripMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function gelar_lulusan()
    {
        return $this->hasMany(GelarLulusan::class, 'id_gelar_lulusan', 'id');
    }

    public function predikat_kelulusan()
    {
        return $this->belongsTo(PredikatKelulusan::class, 'id_predikat_kelulusan', 'id');
    }

    public function periode_wisuda()
    {
        return $this->hasMany(PeriodeWisuda::class, 'periode', 'wisuda_ke');
    }

    public function bku_prodi()
    {
        return $this->belongsTo(BkuProgramStudi::class, 'id_bku_prodi', 'id');
    }

    public function bebas_pustaka()
    {
        return $this->hasOne(BebasPustaka::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function lulus_do()
    {
        return $this->hasOne(LulusDo::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function getIdTanggalSkYudisiumAttribute()
    {
        Carbon::setLocale('id');
        return $this->tanggal_sk_yudisium ?  Carbon::createFromFormat('Y-m-d', $this->tgl_sk_yudisium)->translatedFormat('d F Y') : '-';
    }

    public function getApprovedTextAttribute()
    {
        $status = [
            '0' => 'Belum Diapproved',
            '1' => 'Disetujui Prodi',
            '2' => 'Disetujui Fakultas',
            '3' => 'Disetujui Dir. Akademik',
            '97' => 'Ditolak Prodi',
            '98' => 'Ditolak Fakultas',
            '99' => 'Ditolak Dir. Akademik',
        ];

        return $status[$this->approved];
    }

    public function getMasaStudiAttribute()
    {
        // buat ... tahun, ... bulan dari riwayat_pendidikan->tanggal_daftar sampai this->tanggal_sk_yudisium
        $tgl_daftar = Carbon::createFromFormat('Y-m-d', $this->riwayat_pendidikan->tanggal_daftar);
        $tgl_yudisium = Carbon::createFromFormat('Y-m-d', $this->tgl_sk_yudisium);
        $masa_studi = $tgl_daftar->diffInMonths($tgl_yudisium);
        $tahun = ceil($masa_studi / 12);
        $bulan = $masa_studi % 12;
        return $tahun . ' tahun, ' . $bulan . ' bulan';
    }

    public function getMasaStudiEnAttribute()
    {
        // buat ... tahun, ... bulan dari riwayat_pendidikan->tanggal_daftar sampai this->tanggal_sk_yudisium
        $tgl_daftar = Carbon::createFromFormat('Y-m-d', $this->riwayat_pendidikan->tanggal_daftar);
        $tgl_yudisium = Carbon::createFromFormat('Y-m-d', $this->tgl_sk_yudisium);
        $masa_studi = $tgl_daftar->diffInMonths($tgl_yudisium);
        $tahun = ceil($masa_studi / 12);
        $bulan = $masa_studi % 12;
        return $tahun . ' years, ' . $bulan . ' months';
    }

    public function getStatusAttribute()
    {
        $status = [
            '0' => 'Belum Diapproved',
            '1' => 'Disetujui Prodi',
            '2' => 'Disetujui Fakultas',
            '3' => 'Disetujui BAK',
            '97' => 'Ditolak Prodi',
            '98' => 'Ditolak Fakultas',
            '99' => 'Ditolak BAK',
        ];
    }

}
