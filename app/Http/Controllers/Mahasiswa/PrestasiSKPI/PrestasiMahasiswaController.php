<?php

namespace App\Http\Controllers\Mahasiswa\PrestasiSKPI;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Referensi\JenisPrestasi;
use App\Models\Referensi\TingkatPrestasi;
use App\Models\Semester;
use App\Models\SKPI;
use App\Models\SKPIBidangKegiatan;
use App\Models\SKPIJenisKegiatan;
use App\Models\SKPISubBidangKegiatan;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use App\Models\Dosen\PenugasanDosen;
use App\Models\SemesterAktif;

class PrestasiMahasiswaController extends Controller
{
    public function prestasi_mahasiswa()
    {
        $id_reg_mhs = auth()->user()->fk_id;

        // Ambil data riwayat pendidikan pelapor untuk mendapatkan id_mahasiswa
        $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();
        $id_mhs  = $riwayat ? $riwayat->id_mahasiswa : null;

        // Mengambil semua PrestasiMahasiswa milik mahasiswa pelapor,
        // serta mengecek relasi SKPI jika ada (tanpa membuang data yang skpi-nya NULL)
        $data = PrestasiMahasiswa::with([
                'skpi' => function ($query) use ($id_reg_mhs) {
                    $query->where('id_registrasi_mahasiswa', $id_reg_mhs);
                },
                'skpi.jenisSkpi',
                'skpi.jenisSkpi.bidang',
                'skpi.jenisSkpi.subBidang'
            ])
            ->where('id_mahasiswa', $id_mhs)
            ->orderBy('created_at', 'desc')
            ->get();

            // dd($data); // Debugging line to inspect the retrieved data
        return view('mahasiswa.prestasi-skpi.index', compact('data'));
    }

    public function tambah_prestasi_mahasiswa()
    {
        $id_reg_mhs = auth()->user()->fk_id;

        $jenis_prestasi = SKPIJenisKegiatan::where('sub_bidang_id', 1)
            ->orderBy('id', 'ASC')
            ->pluck('nama_jenis', 'id')
            ->unique();

        $tingkat_prestasi = TingkatPrestasi::orderBy('id_tingkat_prestasi')
                            ->where('id_tingkat_prestasi', '>', 2)
                            ->where('id_tingkat_prestasi', '<', 7) // Exclude "Belmawa"
                            ->get();

        

        $bidang = SKPIBidangKegiatan::all();
        $sub_bidang = SKPISubBidangKegiatan::all();
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

        $tahun_sekarang = date('Y');
        // $tahun_list = range($tahun_sekarang, 2024);

        return view('mahasiswa.prestasi-skpi.create', compact(
            'data',
            'tingkat_prestasi',
            'jenis_prestasi',
            'bidang',
            'sub_bidang',
            'tahun_sekarang'
        ));
    }

    public function store_prestasi_mahasiswa(Request $request) 
{
    $request->validate([
        'kategori_pendanaan'    => 'required',
        'id_tingkat_prestasi'   => 'required|exists:tingkat_prestasis,id_tingkat_prestasi',
        'kategori_prestasi'     => 'required',
        'nama_kompetisi'        => 'required|string|max:255',
        'nama_cabang'           => 'required|string|max:255',
        'peringkat'             => 'required',
        'penyelenggara'         => 'required|string|max:255',
        'jumlah_pt_peserta'     => 'required|numeric',
        'kepesertaan'           => 'required', // 0: Individu, 1: Kelompok
        'bentuk'                => 'required',
        'url_kompetisi'         => 'nullable|url',
        'url_sertifikat'        => 'required|url',
        'url_foto_upp'          => 'nullable|url',
        'url_undangan'          => 'nullable|url',
        'url_sk_pembimbing'     => 'nullable|url',
        'no_sk_pembimbing'      => 'nullable|string',
        'tanggal_sk_pembimbing' => 'nullable|date',
        'tanggal_sertifikat'    => [
            'required',
            'date',
            'after_or_equal:' . date('Y-01-01'),
            'before_or_equal:' . date('Y-12-31'),
        ],
        'mahasiswa'             => 'nullable|array',
    ], [
        'url_sertifikat.required'            => 'URL dokumen sertifikat wajib diisi.',
        'url_sertifikat.url'                 => 'Format URL sertifikat tidak valid.',
        'tanggal_sertifikat.after_or_equal'  => 'Tanggal sertifikat harus di tahun berjalan (' . date('Y') . ').',
        'tanggal_sertifikat.before_or_equal' => 'Tanggal sertifikat harus di tahun berjalan (' . date('Y') . ').',
    ]);

    DB::beginTransaction();

    try {
        $id_reg_mhs = auth()->user()->fk_id;
        $riwayatPelapor = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->firstOrFail();

        // 1. CARI REFERENSI JENIS PRESTASI (DIKTI) DAN TINGKAT PRESTASI (SEPERTI DITMAWA)
        $jenis_prestasi   = JenisPrestasi::where('id_jenis_prestasi', $request->kategori_prestasi)->first();
        $tingkat_prestasi = TingkatPrestasi::where('id_tingkat_prestasi', $request->id_tingkat_prestasi)->first();

        // dd($jenis_prestasi, $tingkat_prestasi, $request->all()); // Debugging line to inspect variables

        // 2. DETERMINASI SKPI_JENIS_KEGIATAN
        $kat       = (string) $request->kategori_prestasi;
        $idTingkat = (string) $request->id_tingkat_prestasi;
        $peringkat = (string) $request->peringkat;

        if ($kat === '3') {
            $namaJenisSKPI = 'Kompetisi Olahraga';
        } elseif ($kat === '2') {
            $namaJenisSKPI = 'Kompetisi Seni/Budaya';
        } else {
            $namaJenisSKPI = 'MTQ/Lomba Keagamaan';
        }

        $isJuara = in_array($peringkat, ['1', '2', '3', 'JUARA1', 'JUARA2', 'JUARA3']);

        if ($idTingkat === '6') {
            $kriteriaSKPI = $isJuara 
                ? 'Juara pada Tingkat Internasional' 
                : 'Berpartisipasi pada Tingkat Internasional';
        } elseif ($idTingkat === '5') {
            $kriteriaSKPI = $isJuara 
                ? 'Juara pada Tingkat Nasional' 
                : 'Berpartisipasi baik pada Tingkat Nasional atau Regional';
        } else {
            $kriteriaSKPI = $isJuara 
                ? 'Juara pada Tingkat Regional' 
                : 'Berpartisipasi baik pada Tingkat Nasional atau Regional';
        }

        $jenis_skpi = SKPIJenisKegiatan::where('bidang_id', 4)
            ->where('sub_bidang_id', 1)
            ->where('nama_jenis', $namaJenisSKPI)
            ->where('kriteria', $kriteriaSKPI)
            ->first();

        if (!$jenis_skpi) {
            $jenis_skpi = SKPIJenisKegiatan::where('sub_bidang_id', 1)->first();
        }

        $id_aktivitas = (string) Str::uuid();

        // Determinasi Semester
        $tgl_sertifikat = \Carbon\Carbon::parse($request->tanggal_sertifikat);
        $calculated_id_semester = ($tgl_sertifikat->month >= 1 && $tgl_sertifikat->month <= 7)
            ? ($tgl_sertifikat->year - 1) . '2'
            : $tgl_sertifikat->year . '1';

        $semester = Semester::where('id_semester', $calculated_id_semester)->first();
        $semesterAktif = SemesterAktif::leftJoin('semesters', 'semesters.id_semester', 'semester_aktifs.id_semester')->first();

        $id_semester_final   = $semester ? $semester->id_semester : ($semesterAktif ? $semesterAktif->id_semester : $calculated_id_semester);
        $nama_semester_final = $semester ? $semester->nama_semester : ($semesterAktif ? $semesterAktif->nama_semester : $calculated_id_semester);

        $isKelompok = ($request->kepesertaan == '1');
        $nama_jenis_anggota = $isKelompok ? 'Kelompok' : 'Personal';

        // 3. CREATE AKTIVITAS MAHASISWA
        AktivitasMahasiswa::create([
            'feeder'               => 0,
            'id_aktivitas'         => $id_aktivitas,
            'program_mbkm'         => 0,
            'nama_program_mbkm'    => 'Mandiri',
            'jenis_anggota'        => $request->kepesertaan,
            'nama_jenis_anggota'   => $nama_jenis_anggota,
            'id_jenis_aktivitas'   => 12,
            'nama_jenis_aktivitas' => 'Kompetisi',
            'id_prodi'             => $riwayatPelapor->id_prodi,
            'nama_prodi'           => $riwayatPelapor->nama_program_studi,
            'id_semester'          => $id_semester_final,
            'nama_semester'        => $nama_semester_final,
            'judul'                => $request->nama_kompetisi,
            'keterangan'           => $request->keterangan,
            'lokasi'               => $request->penyelenggara,
            'sk_tugas'             => $request->no_sk_pembimbing,
            'tanggal_sk_tugas'     => $request->tanggal_sk_pembimbing ?? $request->tanggal_sertifikat,
            'untuk_kampus_merdeka' => 0,
            'asal_data'            => 9,
            'status_sync'          => 'belum sync'
        ]);

        // 4. SUSUN LIST SELURUH MAHASISWA (PELAPOR + ANGGOTA)
        $listMahasiswa = [];
        
        // Mahasiswa Pelapor
        $listMahasiswa[] = [
            'riwayat'          => $riwayatPelapor,
            'jenis_peran'      => $isKelompok ? '1' : '3',
            'nama_jenis_peran' => $isKelompok ? 'Ketua' : 'Personal',
        ];

        // Anggota Tambahan (Jika Kelompok)
        if ($isKelompok && !empty($request->mahasiswa)) {
            foreach ($request->mahasiswa as $id_mhs) {
                if (!empty($id_mhs)) {
                    $riwayatAnggota = RiwayatPendidikan::where('id_mahasiswa', $id_mhs)
                        ->orWhere('id_registrasi_mahasiswa', $id_mhs)
                        ->whereNull('id_jenis_keluar')
                        ->first();

                    if ($riwayatAnggota && $riwayatAnggota->id_registrasi_mahasiswa != $riwayatPelapor->id_registrasi_mahasiswa) {
                        $listMahasiswa[] = [
                            'riwayat'          => $riwayatAnggota,
                            'jenis_peran'      => '2',
                            'nama_jenis_peran' => 'Anggota',
                        ];
                    }
                }
            }
        }

        // 5. LOOP INSERT PRESTASI, SKPI, DAN ANGGOTA AKTIVITAS
        foreach ($listMahasiswa as $item) {
            $mhs           = $item['riwayat'];
            $id_prestasi   = (string) Str::uuid();
            $jenisPeran    = $item['jenis_peran'];
            $namaJenisPeran= $item['nama_jenis_peran'];

            // A. Create PrestasiMahasiswa (Kategori Prestasi disesuaikan seperti Ditmawa)
            PrestasiMahasiswa::create([
                'id_prestasi'           => $id_prestasi,
                'approved'              => 0,
                'id_mahasiswa'          => $mhs->id_mahasiswa,
                'nama_mahasiswa'        => $mhs->nama_mahasiswa,
                'kategori_prestasi'     => $request->kategori_pendanaan,
                'id_jenis_prestasi'     => $jenis_prestasi ? $jenis_prestasi->id_jenis_prestasi : 9,
                'nama_jenis_prestasi'   => $jenis_prestasi ? $jenis_prestasi->nama_jenis_prestasi : 'Lain-lain',
                'id_tingkat_prestasi'   => $tingkat_prestasi ? $tingkat_prestasi->id_tingkat_prestasi : $request->id_tingkat_prestasi,
                'nama_tingkat_prestasi' => $tingkat_prestasi ? $tingkat_prestasi->nama_tingkat_prestasi : '-',
                'nama_prestasi'         => $request->nama_kompetisi,
                'tahun_prestasi'        => date('Y', strtotime($request->tanggal_sertifikat)),
                'penyelenggara'         => $request->penyelenggara,
                'peringkat'             => $request->peringkat,
                'id_aktivitas'          => $id_aktivitas,
                'kategori_simkatmawa'   => $request->kategori_prestasi,
                'jumlah_unit_peserta'   => $request->jumlah_pt_peserta,
                'bentuk'                => $request->bentuk,
                'url_kompetisi'         => $request->url_kompetisi,
                'file_prestasi'         => $request->url_sertifikat,
                'file_foto_upp'         => $request->url_foto_upp,
                'file_undangan'         => $request->url_undangan,
                'sk_pembimbing'         => $request->url_sk_pembimbing,
                'simkatmawa'            => 0,
                'pdunsri'               => 0,
                'feeder'                => 0,
                'status_sync'           => 'belum sync'
            ]);

            // B. Create SKPI
            SKPI::create([
                'id_registrasi_mahasiswa' => $mhs->id_registrasi_mahasiswa,
                'id_prodi'                => $mhs->id_prodi,
                'id_semester'             => $id_semester_final,
                'nama_kegiatan'           => $request->nama_kompetisi,
                'tahun'                   => date('Y', strtotime($request->tanggal_sertifikat)),
                'id_jenis_skpi'           => optional($jenis_skpi)->id,
                'nama_jenis_skpi'         => optional($jenis_skpi)->nama_jenis,
                'id_prestasi'             => $id_prestasi,
                'skor'                    => optional($jenis_skpi)->skor ?? 0,
                'file_pendukung'          => $request->url_sertifikat,
                'approved'                => 0,
            ]);

            // C. Create AnggotaAktivitasMahasiswa
            AnggotaAktivitasMahasiswa::create([
                'feeder'                  => 0,
                'id_anggota'              => (string) Str::uuid(),
                'id_aktivitas'            => $id_aktivitas,
                'judul'                   => $request->nama_kompetisi,
                'id_registrasi_mahasiswa' => $mhs->id_registrasi_mahasiswa,
                'nim'                     => $mhs->nim,
                'nama_mahasiswa'          => $mhs->nama_mahasiswa,
                'jenis_peran'             => $jenisPeran,
                'nama_jenis_peran'        => $namaJenisPeran,
                'status_sync'             => 'belum sync'
            ]);
        }

        // 6. INSERT DOSEN PEMBIMBING (BimbingMahasiswa)
        if ($idTingkat === '6') {
            $id_kategori_kegiatan = $isJuara ? 110604 : 110602;
            $nama_kategori_kegiatan = $isJuara 
                ? 'Membimbing mahasiswa mengikuti kompetisi dibidang akademik dan kemahasiswaan bereputasi dan mencapai juara tingkat Internasional'
                : 'Membimbing mahasiswa menghasilkan produk saintifik bereputasi dan mendapat pengakuan tingkat Internasional';
        } elseif ($idTingkat === '5') {
            $id_kategori_kegiatan = $isJuara ? 110605 : 110603;
            $nama_kategori_kegiatan = $isJuara
                ? 'Membimbing mahasiswa mengikuti kompetisi dibidang akademik dan kemahasiswaan bereputasi dan mencapai juara tingkat Nasional'
                : 'Membimbing mahasiswa menghasilkan produk saintifik bereputasi dan mendapat pengakuan tingkat Nasional';
        } else {
            $id_kategori_kegiatan = 110600;
            $nama_kategori_kegiatan = 'Membina kegiatan mahasiswa di bidang akademik dan kemahasiswaan, termasuk dalam kegiatan ini adalah membimbing mahasiswa menghasilkan produk saintifik, membimbing mahasiswa mengikuti kompetisi di bidang akademik dan kemahasiswaan';
        }

        if (!empty($request->dosen_bimbing)) {
            foreach ($request->dosen_bimbing as $idx => $id_dosen) {
                if (!empty($id_dosen)) {
                    $dosen = PenugasanDosen::where('id_dosen', $id_dosen)->first();

                    BimbingMahasiswa::create([
                        'feeder'                 => 0,
                        'approved'               => 0,
                        'approved_dosen'         => 0,
                        'id_bimbing_mahasiswa'   => (string) Str::uuid(),
                        'id_aktivitas'           => $id_aktivitas,
                        'judul'                  => $request->nama_kompetisi,
                        'id_kategori_kegiatan'   => $id_kategori_kegiatan,
                        'nama_kategori_kegiatan' => $nama_kategori_kegiatan,
                        'id_dosen'               => $id_dosen,
                        'nidn'                   => $dosen ? $dosen->nidn : NULL,
                        'nuptk'                  => $dosen ? $dosen->nuptk : NULL,
                        'nama_dosen'             => $dosen ? $dosen->nama_dosen : NULL,
                        'pembimbing_ke'          => $idx + 1,
                        'status_sync'            => 'belum sync',
                        // 'sk_tugas'               => $request->no_sk_pembimbing,
                        // 'file_surat_tugas'       => $request->url_sk_pembimbing,
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('mahasiswa.prestasi-skpi.index')
            ->with('success', 'Data prestasi berhasil dilaporkan.');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
}

    public function edit($id)
{
    // Cari data PrestasiMahasiswa berdasarkan id_prestasi
    $prestasi = PrestasiMahasiswa::with(['skpi', 'aktivitas_mahasiswa'])->where('id_prestasi', $id)->first();

    if (!$prestasi) {
        // Fallback jika id yang dikirim milik tabel SKPI
        $skpi = SKPI::with('prestasi.aktivitas_mahasiswa')->find($id);
        $prestasi = $skpi ? $skpi->prestasi : null;
    }

    if (!$prestasi) {
        return redirect()->route('mahasiswa.prestasi-skpi.index')
            ->with('error', 'Data prestasi tidak ditemukan.');
    }

    // Proteksi approval
    if ($prestasi->approved > 0 && $prestasi->approved < 97 && !empty($prestasi->skpi) && $prestasi->skpi->approved > 0) {
        return redirect()->route('mahasiswa.prestasi-skpi.index')
            ->with('error', 'Data yang sudah diverifikasi tidak dapat diedit.');
    }

    $tingkat_prestasi = TingkatPrestasi::orderBy('id_tingkat_prestasi')->get();

    // Ambil daftar dosen pembimbing milik aktivitas ini
    $dosen_bimbing = BimbingMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->get();

    return view('mahasiswa.prestasi-skpi.edit', compact(
        'prestasi',
        'tingkat_prestasi',
        'dosen_bimbing'
    ));
}

public function update(Request $request, $id)
{
    // Cari data PrestasiMahasiswa
    $prestasi = PrestasiMahasiswa::with(['skpi', 'aktivitas_mahasiswa'])->where('id_prestasi', $id)->first();

    if (!$prestasi) {
        $skpi = SKPI::with('prestasi.aktivitas_mahasiswa')->find($id);
        $prestasi = $skpi ? $skpi->prestasi : null;
    }

    if (!$prestasi) {
        return redirect()->back()->with('error', 'Data prestasi tidak ditemukan.');
    }

    if ($prestasi->approved > 0 && $prestasi->approved < 97 && !empty($prestasi->skpi) && $prestasi->skpi->approved > 0) {
        return redirect()->back()->with('error', 'Data yang sudah diverifikasi tidak dapat diedit.');
    }

    $request->validate([
        'kategori_pendanaan'    => 'required',
        'id_tingkat_prestasi'   => 'required|exists:tingkat_prestasis,id_tingkat_prestasi',
        'kategori_prestasi'     => 'required',
        'nama_kompetisi'        => 'required|string|max:255',
        'nama_cabang'           => 'required|string|max:255',
        'peringkat'             => 'required',
        'penyelenggara'         => 'required|string|max:255',
        'jumlah_pt_peserta'     => 'required|numeric',
        'kepesertaan'           => 'required',
        'bentuk'                => 'required',
        'url_kompetisi'         => 'nullable|url',
        'url_sertifikat'        => 'required|url',
        'url_foto_upp'          => 'nullable|url',
        'url_undangan'          => 'nullable|url',
        'url_sk_pembimbing'     => 'nullable|url',
        'no_sk_pembimbing'      => 'nullable|string',
        'tanggal_sk_pembimbing' => 'nullable|date',
        'tanggal_sertifikat'    => [
            'required',
            'date',
            'after_or_equal:' . date('Y-01-01'),
            'before_or_equal:' . date('Y-12-31'),
        ],
    ]);

    DB::beginTransaction();

    try {
        // 1. CARI REFERENSI DIKTI & TINGKAT PRESTASI
        $jenis_prestasi   = JenisPrestasi::where('id_jenis_prestasi', $request->kategori_prestasi)->first();
        $tingkat_prestasi = TingkatPrestasi::where('id_tingkat_prestasi', $request->id_tingkat_prestasi)->first();

        // 2. DETERMINASI RECALCULATED SKPI_JENIS_KEGIATAN
        $kat       = (string) $request->kategori_prestasi;
        $idTingkat = (string) $request->id_tingkat_prestasi;
        $peringkat = (string) $request->peringkat;

        if ($kat === '3') {
            $namaJenisSKPI = 'Kompetisi Olahraga';
        } elseif ($kat === '2') {
            $namaJenisSKPI = 'Kompetisi Seni/Budaya';
        } else {
            $namaJenisSKPI = 'MTQ/Lomba Keagamaan';
        }

        $isJuara = in_array($peringkat, ['1', '2', '3', 'JUARA1', 'JUARA2', 'JUARA3']);

        if ($idTingkat === '6') {
            $kriteriaSKPI = $isJuara ? 'Juara pada Tingkat Internasional' : 'Berpartisipasi pada Tingkat Internasional';
        } elseif ($idTingkat === '5') {
            $kriteriaSKPI = $isJuara ? 'Juara pada Tingkat Nasional' : 'Berpartisipasi baik pada Tingkat Nasional atau Regional';
        } else {
            $kriteriaSKPI = $isJuara ? 'Juara pada Tingkat Regional' : 'Berpartisipasi baik pada Tingkat Nasional atau Regional';
        }

        $jenis_skpi = SKPIJenisKegiatan::where('bidang_id', 4)
            ->where('sub_bidang_id', 1)
            ->where('nama_jenis', $namaJenisSKPI)
            ->where('kriteria', $kriteriaSKPI)
            ->first();

        if (!$jenis_skpi) {
            $jenis_skpi = SKPIJenisKegiatan::where('sub_bidang_id', 1)->first();
        }

        // 3. UPDATE AKTIVITAS MAHASISWA
        if ($prestasi->aktivitas_mahasiswa) {
            $isKelompok = ($request->kepesertaan == '1');
            $prestasi->aktivitas_mahasiswa->update([
                'jenis_anggota'      => $request->kepesertaan,
                'nama_jenis_anggota' => $isKelompok ? 'Kelompok' : 'Personal',
                'judul'              => $request->nama_kompetisi,
                'keterangan'         => $request->keterangan,
                'lokasi'             => $request->penyelenggara,
                'sk_tugas'           => $request->no_sk_pembimbing,
                'tanggal_sk_tugas'   => $request->tanggal_sk_pembimbing ?? $request->tanggal_sertifikat,
            ]);
        }

        // 4. UPDATE SEMUA MAHASISWA DALAM AKTIVITAS INI (JIKA KELOMPOK)
        PrestasiMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->update([
            'approved'              => 0,
            'kategori_prestasi'     => $request->kategori_pendanaan,
            'id_jenis_prestasi'     => $jenis_prestasi ? $jenis_prestasi->id_jenis_prestasi : 9,
            'nama_jenis_prestasi'   => $jenis_prestasi ? $jenis_prestasi->nama_jenis_prestasi : 'Lain-lain',
            'id_tingkat_prestasi'   => $tingkat_prestasi ? $tingkat_prestasi->id_tingkat_prestasi : $request->id_tingkat_prestasi,
            'nama_tingkat_prestasi' => $tingkat_prestasi ? $tingkat_prestasi->nama_tingkat_prestasi : '-',
            'nama_prestasi'         => $request->nama_kompetisi,
            'tahun_prestasi'        => date('Y', strtotime($request->tanggal_sertifikat)),
            'penyelenggara'         => $request->penyelenggara,
            'peringkat'             => $request->peringkat,
            'kategori_simkatmawa'   => $request->kategori_prestasi,
            'jumlah_unit_peserta'   => $request->jumlah_pt_peserta,
            'bentuk'                => $request->bentuk,
            'url_kompetisi'         => $request->url_kompetisi,
            'file_prestasi'         => $request->url_sertifikat,
            'file_foto_upp'         => $request->url_foto_upp,
            'file_undangan'         => $request->url_undangan,
            'sk_pembimbing'         => $request->url_sk_pembimbing,
        ]);

        // 5. UPDATE DATA SKPI (ATAU CREATE JIKA SKPI SEBELUMNYA NULL)
        $id_reg_mhs = auth()->user()->fk_id;
        $riwayatPelapor = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

        $tgl_sertifikat = \Carbon\Carbon::parse($request->tanggal_sertifikat);
        $calculated_id_semester = ($tgl_sertifikat->month >= 1 && $tgl_sertifikat->month <= 7)
            ? ($tgl_sertifikat->year - 1) . '2'
            : $tgl_sertifikat->year . '1';

        if ($prestasi->skpi) {
            $prestasi->skpi->update([
                'nama_kegiatan'   => $request->nama_kompetisi,
                'tahun'           => date('Y', strtotime($request->tanggal_sertifikat)),
                'id_jenis_skpi'   => optional($jenis_skpi)->id,
                'nama_jenis_skpi' => optional($jenis_skpi)->nama_jenis,
                'skor'            => optional($jenis_skpi)->skor ?? 0,
                'file_pendukung'  => $request->url_sertifikat,
            ]);
        } else if ($riwayatPelapor) {
            SKPI::create([
                'id_registrasi_mahasiswa' => $riwayatPelapor->id_registrasi_mahasiswa,
                'id_prodi'                => $riwayatPelapor->id_prodi,
                'id_semester'             => $calculated_id_semester,
                'nama_kegiatan'           => $request->nama_kompetisi,
                'tahun'                   => date('Y', strtotime($request->tanggal_sertifikat)),
                'id_jenis_skpi'           => optional($jenis_skpi)->id,
                'nama_jenis_skpi'         => optional($jenis_skpi)->nama_jenis,
                'id_prestasi'             => $prestasi->id_prestasi,
                'skor'                    => optional($jenis_skpi)->skor ?? 0,
                'file_pendukung'          => $request->url_sertifikat,
                'approved'                => 0,
            ]);
        }

        // 6. SYNC / RE-INSERT DOSEN PEMBIMBING
        if ($idTingkat === '6') {
            $id_kategori_kegiatan = $isJuara ? 110604 : 110602;
            $nama_kategori_kegiatan = $isJuara 
                ? 'Membimbing mahasiswa mengikuti kompetisi dibidang akademik dan kemahasiswaan bereputasi dan mencapai juara tingkat Internasional'
                : 'Membimbing mahasiswa menghasilkan produk saintifik bereputasi dan mendapat pengakuan tingkat Internasional';
        } elseif ($idTingkat === '5') {
            $id_kategori_kegiatan = $isJuara ? 110605 : 110603;
            $nama_kategori_kegiatan = $isJuara
                ? 'Membimbing mahasiswa mengikuti kompetisi dibidang akademik dan kemahasiswaan bereputasi dan mencapai juara tingkat Nasional'
                : 'Membimbing mahasiswa menghasilkan produk saintifik bereputasi dan mendapat pengakuan tingkat Nasional';
        } else {
            $id_kategori_kegiatan = 110600;
            $nama_kategori_kegiatan = 'Membina kegiatan mahasiswa di bidang akademik dan kemahasiswaan, termasuk dalam kegiatan ini adalah membimbing mahasiswa menghasilkan produk saintifik, membimbing mahasiswa mengikuti kompetisi di bidang akademik dan kemahasiswaan';
        }

        // Hapus bimbingan dosen lama dan ganti dengan yang baru
        BimbingMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->delete();

        if (!empty($request->dosen_bimbing)) {
            foreach ($request->dosen_bimbing as $idx => $id_dosen) {
                if (!empty($id_dosen)) {
                    $dosen = PenugasanDosen::where('id_dosen', $id_dosen)->first();

                    BimbingMahasiswa::create([
                        'feeder'                 => 0,
                        'approved'               => 0,
                        'approved_dosen'         => 0,
                        'id_bimbing_mahasiswa'   => (string) Str::uuid(),
                        'id_aktivitas'           => $prestasi->id_aktivitas,
                        'judul'                  => $request->nama_kompetisi,
                        'id_kategori_kegiatan'   => $id_kategori_kegiatan,
                        'nama_kategori_kegiatan' => $nama_kategori_kegiatan,
                        'id_dosen'               => $id_dosen,
                        'nidn'                   => $dosen ? $dosen->nidn : NULL,
                        'nuptk'                  => $dosen ? $dosen->nuptk : NULL,
                        'nama_dosen'             => $dosen ? $dosen->nama_dosen : NULL,
                        'pembimbing_ke'          => $idx + 1,
                        'status_sync'            => 'belum sync',
                    ]);
                }
            }
        }

        DB::commit();

        return redirect()->route('mahasiswa.prestasi-skpi.index')
            ->with('success', 'Data prestasi dan SKPI berhasil diperbarui.');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
    }
}

    public function delete_prestasi_mahasiswa($id)
{
    DB::beginTransaction();

    try {
        // 1. Cari data PrestasiMahasiswa berdasarkan id_prestasi
        $prestasi = PrestasiMahasiswa::with('skpi')->where('id_prestasi', $id)->first();

        if ($prestasi) {
            // Cek proteksi approval di Prestasi atau SKPI
            $isApproved = ($prestasi->approved > 0) && !empty($prestasi->skpi) && ($prestasi->skpi->approved > 0);

            if ($isApproved) {
                return redirect()->back()
                    ->with('error', 'Data yang sudah diverifikasi tidak dapat dihapus.');
            }

            // Hapus record SKPI jika ada
            if ($prestasi->skpi) {
                $prestasi->skpi->delete();
            }

            // Hapus record PrestasiMahasiswa
            $prestasi->delete();
        } else {
            // 2. Fallback: Jika parameter $id yang dikirim adalah ID milik tabel SKPI
            $skpi = SKPI::with('prestasi')->find($id);

            if (!$skpi) {
                return redirect()->back()
                    ->with('error', 'Data prestasi tidak ditemukan.');
            }

            $isApproved = ($skpi->approved > 0) && !empty($skpi->prestasi) && ($skpi->prestasi->approved > 0);

            if ($isApproved) {
                return redirect()->back()
                    ->with('error', 'Data yang sudah diverifikasi tidak dapat dihapus.');
            }

            if ($skpi->prestasi) {
                $skpi->prestasi->delete();
            }

            $skpi->delete();
        }

        DB::commit();

        return redirect()->back()
            ->with('success', 'Data prestasi dan SKPI berhasil dihapus.');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
    }
}

    public function get_mahasiswa(Request $request)
    {
        try {
            $search   = $request->get('q');
            $selected = $request->get('selected', []);

            $query = RiwayatPendidikan::with('prodi')->whereNull('id_jenis_keluar');

            if (!empty($selected) && is_array($selected)) {
                $query->whereNotIn('id_registrasi_mahasiswa', $selected)
                    ->whereNotIn('id_mahasiswa', $selected);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_mahasiswa', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
                });
            }

            $mahasiswa = $query->orderBy('nama_mahasiswa', 'asc')->limit(25)->get();

            $results = [];
            foreach ($mahasiswa as $item) {
                $namaProdi = $item->nama_program_studi ?? optional($item->prodi)->nama_program_studi ?? '-';
                $results[] = [
                    'id'   => $item->id_registrasi_mahasiswa ?? $item->id_mahasiswa,
                    'text' => $item->nim . " - " . $item->nama_mahasiswa . " (" . $namaProdi . ")"
                ];
            }

            return response()->json(['results' => $results]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function get_dosen(Request $request)
    {
        try {
            $search   = $request->get('q');
            $selected = $request->get('selected', []); // Array ID yang sudah dipilih

            $tahun_ajaran = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                            ->first();

            $query = PenugasanDosen::whereHas('biodata', function($q) {
                        $q->where('id_jenis_sdm', 12); // Hanya ambil dosen yang id_sdm = 12 di tabel biodata
                    })
                    ->where('id_tahun_ajaran', optional($tahun_ajaran)->id_tahun_ajaran)
                    ->orderBy('nama_dosen', 'asc');

            // Kecualikan dosen yang sudah dipilih
            if (!empty($selected) && is_array($selected)) {
                $query->whereNotIn('id_dosen', $selected);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_dosen', 'like', "%{$search}%")
                    ->orWhere('nidn', 'like', "%{$search}%");
                });
            }

            $dosen = $query->orderBy('nama_dosen', 'asc')->limit(25)->get();

            $results = [];
            foreach ($dosen as $item) {
                $results[] = [
                    'id'   => $item->id_dosen ?? $item->id_penugasan_dosen,
                    'text' => ($item->nidn ?? $item->nuptk) . " - " . $item->nama_dosen . " (" . ($item->nama_program_studi ?? '-') . ")"
                ];
            }

            return response()->json(['results' => $results]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}