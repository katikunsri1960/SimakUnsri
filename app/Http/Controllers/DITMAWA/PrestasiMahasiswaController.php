<?php

namespace App\Http\Controllers\DITMAWA;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use Illuminate\Http\Request;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Referensi\TingkatPrestasi;
use App\Models\Referensi\JenisPrestasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Dosen\PenugasanDosen;
use App\Models\SemesterAktif;
use App\Models\Semester;
use App\Models\SKPI;
use App\Models\SKPIJenisKegiatan;


class PrestasiMahasiswaController extends Controller
{
    public function belmawa_index(Request $request)
    {
        $fakultas_view = $request->get('fakultas_view');
        $prodi_view    = $request->get('prodi_view');
        $tahun_view    = $request->get('tahun_view');

        // 1. Data Master untuk Filter
        $fakultas = Fakultas::select('id', 'nama_fakultas')->get();

        $prodi = ProgramStudi::where('status', 'A')
            ->select('id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan', 'nama_program_studi', 'fakultas_id')
            ->orderBy('id_jenjang_pendidikan', 'ASC')
            ->orderBy('kode_program_studi')
            ->orderBy('nama_program_studi', 'ASC')
            ->get();

        // Ambil daftar tahun prestasi unik untuk Belmawa
        $pilihan_tahun = PrestasiMahasiswa::where('penyelenggara', 'Belmawa')
            ->select('tahun_prestasi')
            ->distinct()
            ->orderBy('tahun_prestasi', 'DESC')
            ->pluck('tahun_prestasi');

        $tahun_aktif = $pilihan_tahun->first() ?? date('Y');
        $selected_tahun = $tahun_view ?? $tahun_aktif;

        // 2. Base Query Prestasi Belmawa
        $query = PrestasiMahasiswa::with(['riwayat_pendidikan', 'riwayat_pendidikan.prodi', 'riwayat_pendidikan.prodi.fakultas'])
            ->where('penyelenggara', 'Belmawa');

        // Filter Fakultas & Prodi melalui Riwayat Pendidikan
        if ($prodi_view && $prodi_view !== 'all') {
            $query->whereHas('riwayat_pendidikan', function ($q) use ($prodi_view) {
                $q->where('id_prodi', $prodi_view);
            });
        } elseif ($fakultas_view && $fakultas_view !== 'all') {
            $query->whereHas('riwayat_pendidikan.prodi', function ($q) use ($fakultas_view) {
                $q->where('fakultas_id', $fakultas_view);
            });
        }

        // Filter Tahun
        if ($selected_tahun !== 'all') {
            $query->where('tahun_prestasi', $selected_tahun);
        }

        $data = $query->orderBy('created_at', 'DESC')->get();

        return view('ditmawa.data-prestasi.prestasi-belmawa', compact(
            'data',
            'fakultas',
            'prodi',
            'pilihan_tahun',
            'fakultas_view',
            'prodi_view',
            'tahun_view',
            'tahun_aktif'
        ));
    }

    public function belmawa_approve($id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 2) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Ditmawa.');
        }

        $prestasi->update([
            'approved' => 3
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil disetujui Ditmawa.');
    }

    public function belmawa_decline(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500'
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.'
        ]);

        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 2) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Ditmawa.');
        }

        $prestasi->update([
            'approved'          => 99,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil ditolak/dibatalkan Ditmawa.');
    }

    public function mandiri_index(Request $request)
    {
        $fakultas_view = $request->get('fakultas_view');
        $prodi_view    = $request->get('prodi_view');
        $tahun_view    = $request->get('tahun_view');

        // 1. Data Master untuk Filter
        $fakultas = Fakultas::select('id', 'nama_fakultas')->get();

        $prodi = ProgramStudi::where('status', 'A')
            ->select('id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan', 'nama_program_studi', 'fakultas_id')
            ->orderBy('id_jenjang_pendidikan', 'ASC')
            ->orderBy('kode_program_studi')
            ->orderBy('nama_program_studi', 'ASC')
            ->get();

        // Ambil daftar tahun prestasi unik untuk Mandiri (Non-Belmawa)
        $pilihan_tahun = PrestasiMahasiswa::where('penyelenggara', '!=', 'Belmawa')
            ->select('tahun_prestasi')
            ->distinct()
            ->orderBy('tahun_prestasi', 'DESC')
            ->pluck('tahun_prestasi');

        $tahun_aktif = $pilihan_tahun->first() ?? date('Y');
        $selected_tahun = $tahun_view ?? $tahun_aktif;

        // 2. Base Query Prestasi Mandiri
        $query = PrestasiMahasiswa::with(['riwayat_pendidikan', 'riwayat_pendidikan.prodi', 'riwayat_pendidikan.prodi.fakultas'])
            ->where('penyelenggara', '!=', 'Belmawa');

        // -------------------------------------------------------------------------------------
        // FILTER: Hanya ambil record prestasi yang mahasiswanya bertindak sebagai Ketua / Personal
        // -------------------------------------------------------------------------------------
        $query->whereExists(function ($subQuery) {
            $subQuery->select(DB::raw(1))
                ->from('anggota_aktivitas_mahasiswas as aam')
                ->join('riwayat_pendidikans as rp', 'rp.id_registrasi_mahasiswa', '=', 'aam.id_registrasi_mahasiswa')
                ->whereColumn('aam.id_aktivitas', 'prestasi_mahasiswas.id_aktivitas')
                ->whereColumn('rp.id_mahasiswa', 'prestasi_mahasiswas.id_mahasiswa')
                ->where(function ($roleQuery) {
                    $roleQuery->whereIn('aam.jenis_peran', ['1', '3'])
                            ->orWhereIn('aam.nama_jenis_peran', ['Ketua', 'Personal', 'ketua', 'personal']);
                });
        });

        // Filter Fakultas & Prodi melalui Riwayat Pendidikan
        if ($prodi_view && $prodi_view !== 'all') {
            $query->whereHas('riwayat_pendidikan', function ($q) use ($prodi_view) {
                $q->where('id_prodi', $prodi_view);
            });
        } elseif ($fakultas_view && $fakultas_view !== 'all') {
            $query->whereHas('riwayat_pendidikan.prodi', function ($q) use ($fakultas_view) {
                $q->where('fakultas_id', $fakultas_view);
            });
        }

        // Filter Tahun
        if ($selected_tahun !== 'all') {
            $query->where('tahun_prestasi', $selected_tahun);
        }

        $data = $query->orderBy('created_at', 'DESC')->get();

        return view('ditmawa.data-prestasi.mandiri.index', compact(
            'data',
            'fakultas',
            'prodi',
            'pilihan_tahun',
            'fakultas_view',
            'prodi_view',
            'tahun_view',
            'tahun_aktif'
        ));
    }

    public function mandiri_approve($id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 2) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Ditmawa.');
        }

        $prestasi->update([
            'approved' => 3
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil disetujui Ditmawa.');
    }

    public function mandiri_decline(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500'
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.'
        ]);

        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 2) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Ditmawa.');
        }

        $prestasi->update([
            'approved'          => 99,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil ditolak/dibatalkan Ditmawa.');
    }

    public function create_mandiri()
    {
        $tingkat_prestasi = TingkatPrestasi::orderBy('id_tingkat_prestasi')
                            ->where('id_tingkat_prestasi', '>', 2)
                            ->where('id_tingkat_prestasi', '<', 7) // Exclude "Belmawa"
                            ->get();
        $jenis_prestasi = JenisPrestasi::orderBy('id_jenis_prestasi')
                            ->get();

        // dd($tingkat_prestasi, $jenis_prestasi);
        return view('ditmawa.data-prestasi.mandiri.create', compact('tingkat_prestasi', 'jenis_prestasi'));
    }

    public function store_mandiri(Request $request)
    {
        $request->validate([
            'kategori_pendanaan'    => 'required',
            'id_tingkat_prestasi'   => 'required',
            'kategori_prestasi'     => 'required',
            'nama_kompetisi'        => 'required|string',
            'nama_cabang'           => 'required|string',
            'peringkat'             => 'required',
            'penyelenggara'         => 'required|string',
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
            'tanggal_sertifikat'    => 'required|date',
            'mahasiswa'             => 'required|array|min:1',
        ], [
            'kategori_prestasi.required' => 'Kategori prestasi wajib dipilih.',
            'url_sertifikat.required'    => 'URL sertifikat wajib diisi.',
            'url_sertifikat.url'         => 'Format URL sertifikat tidak valid.',
        ]);

        DB::beginTransaction();
        try {
            $id_aktivitas = (string) Str::uuid();

            // ----------------------------------------------------
            // DETERMINASI ID_SEMESTER DARI TANGGAL SERTIFIKAT
            // ----------------------------------------------------
            $tgl_sertifikat = \Carbon\Carbon::parse($request->tanggal_sertifikat);

            if ($tgl_sertifikat->month >= 1 && $tgl_sertifikat->month <= 7) {
                $calculated_id_semester = ($tgl_sertifikat->year - 1) . '2';
            } else {
                $calculated_id_semester = $tgl_sertifikat->year . '1';
            }

            $semester = Semester::where('id_semester', $calculated_id_semester)->first();
            $semesterAktif = SemesterAktif::leftJoin('semesters', 'semesters.id_semester', 'semester_aktifs.id_semester')->first();

            $id_semester_final   = $semester ? $semester->id_semester : ($semesterAktif ? $semesterAktif->id_semester : $calculated_id_semester);
            $nama_semester_final = $semester ? $semester->nama_semester : ($semesterAktif ? $semesterAktif->nama_semester : $calculated_id_semester);

            $nama_jenis_anggota = $request->kepesertaan == '0' ? 'Personal' : 'Kelompok';

            // Ambil data prodi dari mahasiswa pertama
            $firstMhs = RiwayatPendidikan::with('prodi')
                ->where('id_mahasiswa', $request->mahasiswa[0])
                ->orWhere('id_registrasi_mahasiswa', $request->mahasiswa[0])
                ->whereNull('id_jenis_keluar')
                ->first();

            $id_prodi_pertama   = $firstMhs ? $firstMhs->id_prodi : NULL;
            $nama_prodi_pertama = $firstMhs ? ($firstMhs->nama_program_studi ?? optional($firstMhs->prodi)->nama_program_studi) : NULL;

            // 1. INSERT AKTIVITAS MAHASISWA
            $data_aktivitas = [
                'feeder'                    => 0,
                'id_aktivitas'              => $id_aktivitas,
                'program_mbkm'              => 0,
                'nama_program_mbkm'         => 'Mandiri',
                'jenis_anggota'             => $request->kepesertaan,
                'nama_jenis_anggota'        => $nama_jenis_anggota,
                'id_jenis_aktivitas'        => 12,
                'nama_jenis_aktivitas'      => 'Kompetisi',
                'id_prodi'                  => $id_prodi_pertama,
                'nama_prodi'                => $nama_prodi_pertama,
                'id_semester'               => $id_semester_final,
                'nama_semester'             => $nama_semester_final,
                'judul'                     => $request->nama_kompetisi,
                'keterangan'                => $request->keterangan,
                'lokasi'                    => $request->lokasi ?? $request->penyelenggara,
                'sk_tugas'                  => $request->no_sk_pembimbing,
                'tanggal_sk_tugas'          => $request->tanggal_sk_pembimbing ?? $request->tanggal_sertifikat,
                'untuk_kampus_merdeka'      => 0,
                'asal_data'                 => 9,
                'status_sync'               => 'belum sync'
            ];
            AktivitasMahasiswa::create($data_aktivitas);

            // ----------------------------------------------------
            // DETERMINASI SKPI_JENIS_KEGIATAN
            // ----------------------------------------------------
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

            // 2, 3, & SKPI. INSERT PRESTASI MAHASISWA, ANGGOTA AKTIVITAS, & SKPI DATA
            $totalMahasiswa = count($request->mahasiswa);
            $jenis_prestasi = JenisPrestasi::where('id_jenis_prestasi', $request->kategori_prestasi)->first();
            $tingkat_prestasi = TingkatPrestasi::where('id_tingkat_prestasi', $request->id_tingkat_prestasi)->first();

            foreach ($request->mahasiswa as $index => $id_mhs) {
                $riwayat = RiwayatPendidikan::where('id_mahasiswa', $id_mhs)
                    ->orWhere('id_registrasi_mahasiswa', $id_mhs)
                    ->whereNull('id_jenis_keluar')
                    ->first();

                if ($riwayat) {
                    $id_prestasi_new = (string) Str::uuid();

                    // A. Insert ke PrestasiMahasiswa
                    PrestasiMahasiswa::create([
                        'id_prestasi'           => $id_prestasi_new,
                        'approved'              => 3, // Otomatis disetujui Ditmawa
                        'id_mahasiswa'          => $riwayat->id_mahasiswa,
                        'nama_mahasiswa'        => $riwayat->nama_mahasiswa,
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

                    // B. Insert ke SKPI Data
                    SKPI::create([
                        'id_registrasi_mahasiswa' => $riwayat->id_registrasi_mahasiswa,
                        'id_prodi'                => $riwayat->id_prodi,
                        'id_semester'             => $id_semester_final,
                        'nama_kegiatan'           => $request->nama_kompetisi,
                        'tahun'                   => date('Y', strtotime($request->tanggal_sertifikat)),
                        'id_jenis_skpi'           => optional($jenis_skpi)->id,
                        'nama_jenis_skpi'         => optional($jenis_skpi)->nama_jenis,
                        'id_prestasi'             => $id_prestasi_new,
                        'skor'                    => optional($jenis_skpi)->skor ?? 0,
                        'file_pendukung'          => $request->url_sertifikat,
                        'approved'                => 3, // Otomatis disetujui Ditmawa
                    ]);

                    // Penentuan Jenis Peran Anggota
                    if ($totalMahasiswa === 1 || $request->kepesertaan == '0') {
                        $jenis_peran = '3';
                        $nama_jenis_peran = 'Personal';
                    } else {
                        $jenis_peran = ($index === 0) ? '1' : '2';
                        $nama_jenis_peran = ($index === 0) ? 'Ketua' : 'Anggota';
                    }

                    // C. Insert Anggota Aktivitas
                    AnggotaAktivitasMahasiswa::create([
                        'feeder'                  => 0,
                        'id_anggota'              => (string) Str::uuid(),
                        'id_aktivitas'            => $id_aktivitas,
                        'judul'                   => $request->nama_kompetisi,
                        'id_registrasi_mahasiswa' => $riwayat->id_registrasi_mahasiswa,
                        'nim'                     => $riwayat->nim,
                        'nama_mahasiswa'          => $riwayat->nama_mahasiswa,
                        'jenis_peran'             => $jenis_peran,
                        'nama_jenis_peran'        => $nama_jenis_peran,
                        'status_sync'             => 'belum sync'
                    ]);
                }
            }

            // ----------------------------------------------------
            // DETERMINASI DINAMIS KATEGORI KEGIATAN PEMBIMBING DOSEN
            // ----------------------------------------------------
            $id_tingkat = (string) $request->id_tingkat_prestasi;
            $peringkat  = (string) $request->peringkat;

            $isJuara = in_array($peringkat, ['1', '2', '3', 'JUARA1', 'JUARA2', 'JUARA3']);

            if ($id_tingkat === '6') { // Internasional
                if ($isJuara) {
                    $id_kategori_kegiatan = 110604;
                    $nama_kategori_kegiatan = 'Membimbing mahasiswa mengikuti kompetisi dibidang akademik dan kemahasiswaan bereputasi dan mencapai juara tingkat Internasional';
                } else {
                    $id_kategori_kegiatan = 110602;
                    $nama_kategori_kegiatan = 'Membimbing mahasiswa menghasilkan produk saintifik bereputasi dan mendapat pengakuan tingkat Internasional';
                }
            } elseif ($id_tingkat === '5') { // Nasional
                if ($isJuara) {
                    $id_kategori_kegiatan = 110605;
                    $nama_kategori_kegiatan = 'Membimbing mahasiswa mengikuti kompetisi dibidang akademik dan kemahasiswaan bereputasi dan mencapai juara tingkat Nasional';
                } else {
                    $id_kategori_kegiatan = 110603;
                    $nama_kategori_kegiatan = 'Membimbing mahasiswa menghasilkan produk saintifik bereputasi dan mendapat pengakuan tingkat Nasional';
                }
            } else { // Provinsi, Kab/Kota, dll.
                $id_kategori_kegiatan = 110600;
                $nama_kategori_kegiatan = 'Membina kegiatan mahasiswa di bidang akademik dan kemahasiswaan, termasuk dalam kegiatan ini adalah membimbing mahasiswa menghasilkan produk saintifik, membimbing mahasiswa mengikuti kompetisi di bidang akademik dan kemahasiswaan';
            }

            // 4. INSERT DOSEN PEMBIMBING KE BIMBING_MAHASISWAS
            if (!empty($request->dosen_bimbing)) {
                foreach ($request->dosen_bimbing as $idx => $id_dosen) {
                    if (!empty($id_dosen)) {
                        $dosen = PenugasanDosen::where('id_dosen', $id_dosen)->first();

                        BimbingMahasiswa::create([
                            'feeder'                 => 0,
                            'approved'               => 1,
                            'approved_dosen'         => 1,
                            'alasan_pembatalan'      => NULL,
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
                            'nilai_proses_bimbingan' => NULL,
                            'status_sync'            => 'belum sync',
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('ditmawa.data-prestasi.mandiri.index')->with('success', 'Data prestasi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $prestasi = PrestasiMahasiswa::with(['skpi', 'aktivitas_mahasiswa', 'riwayat_pendidikan'])
            ->where('id_prestasi', $id)
            ->firstOrFail();

        $dosen_bimbing = BimbingMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->get();
        $anggota_mhs   = AnggotaAktivitasMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->get();

        return view('ditmawa.data-prestasi.mandiri.detail', compact(
            'prestasi',
            'dosen_bimbing',
            'anggota_mhs'
        ));
    }

    public function edit($id)
    {
        $prestasi = PrestasiMahasiswa::with(['skpi', 'aktivitas_mahasiswa', 'riwayat_pendidikan'])
            ->where('id_prestasi', $id)
            ->firstOrFail();

        $tingkat_prestasi = TingkatPrestasi::orderBy('id_tingkat_prestasi')->get();
        $dosen_bimbing = BimbingMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->get();
        $anggota_mhs = AnggotaAktivitasMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->get();

        return view('ditmawa.data-prestasi.mandiri.edit', compact(
            'prestasi',
            'tingkat_prestasi',
            'dosen_bimbing',
            'anggota_mhs'
        ));
    }

    /**
     * Proses Update Data Prestasi Mandiri (Ditmawa)
     */
    public function update(Request $request, $id)
    {
        $prestasi = PrestasiMahasiswa::where('id_prestasi', $id)->firstOrFail();

        $request->validate([
            'kategori_pendanaan'    => 'required',
            'id_tingkat_prestasi'   => 'required',
            'kategori_prestasi'     => 'required',
            'nama_kompetisi'        => 'required|string|max:255',
            'nama_cabang'           => 'required|string|max:255',
            'peringkat'             => 'required',
            'penyelenggara'         => 'required|string|max:255',
            'jumlah_pt_peserta'     => 'required|numeric',
            'bentuk'                => 'required',
            'url_kompetisi'         => 'nullable|url',
            'url_sertifikat'        => 'required|url',
            'url_foto_upp'          => 'nullable|url',
            'url_undangan'          => 'nullable|url',
            'url_sk_pembimbing'     => 'nullable|url',
            'no_sk_pembimbing'      => 'nullable|string',
            'tanggal_sk_pembimbing' => 'nullable|date',
            'tanggal_sertifikat'    => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // 1. CARI REFERENSI DIKTI & TINGKAT PRESTASI
            $jenis_prestasi   = JenisPrestasi::where('id_jenis_prestasi', $request->kategori_prestasi)->first();
            $tingkat_prestasi = TingkatPrestasi::where('id_tingkat_prestasi', $request->id_tingkat_prestasi)->first();

            // 2. RE-DETERMINASI SKPI_JENIS_KEGIATAN
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
            if ($prestasi->id_aktivitas) {
                AktivitasMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->update([
                    'judul'            => $request->nama_kompetisi,
                    'keterangan'       => $request->keterangan,
                    'lokasi'           => $request->penyelenggara,
                    'sk_tugas'         => $request->no_sk_pembimbing,
                    'tanggal_sk_tugas' => $request->tanggal_sk_pembimbing ?? $request->tanggal_sertifikat,
                ]);
            }

            // 4. UPDATE SELURUH RECORD PRESTASI MAHASISWA DALAM AKTIVITAS INI
            PrestasiMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->update([
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

            // 5. UPDATE RECORD SKPI DATA
            SKPI::where('id_prestasi', $id)->update([
                'nama_kegiatan'   => $request->nama_kompetisi,
                'tahun'           => date('Y', strtotime($request->tanggal_sertifikat)),
                'id_jenis_skpi'   => optional($jenis_skpi)->id,
                'nama_jenis_skpi' => optional($jenis_skpi)->nama_jenis,
                'skor'            => optional($jenis_skpi)->skor ?? 0,
                'file_pendukung'  => $request->url_sertifikat,
            ]);

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

            BimbingMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->delete();

            if (!empty($request->dosen_bimbing)) {
                foreach ($request->dosen_bimbing as $idx => $id_dosen) {
                    if (!empty($id_dosen)) {
                        $dosen = PenugasanDosen::where('id_dosen', $id_dosen)->first();

                        BimbingMahasiswa::create([
                            'feeder'                 => 0,
                            'approved'               => 1,
                            'approved_dosen'         => 1,
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
            return redirect()->route('ditmawa.data-prestasi.mandiri.index')
                ->with('success', 'Data prestasi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Approve Laporan Prestasi Mahasiswa oleh Ditmawa (Set approved = 3)
     */
    public function approve($id)
    {
        DB::beginTransaction();
        try {
            // Cari data PrestasiMahasiswa
            $prestasi = PrestasiMahasiswa::where('id_prestasi', $id)->first();

            if (!$prestasi) {
                // Fallback jika ID yang dikirim adalah ID di tabel SKPI atau primary key lain
                $skpi = SKPI::find($id);
                $prestasi = $skpi ? $skpi->prestasi : null;
            }

            if (!$prestasi) {
                return redirect()->back()->with('error', 'Data prestasi tidak ditemukan.');
            }

            // Update status approval pada seluruh prestasi dalam aktivitas yang sama
            PrestasiMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->update([
                'approved' => 3
            ]);

            // Update status approval pada SKPI
            SKPI::where('id_prestasi', $prestasi->id_prestasi)->update([
                'approved' => 3
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Laporan prestasi berhasil disetujui di tingkat Ditmawa.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyetujui prestasi: ' . $e->getMessage());
        }
    }

    /**
     * Decline / Batalkan Laporan Prestasi Mahasiswa oleh Ditmawa (Set approved = 99)
     */
    public function decline(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:255',
        ], [
            'alasan_pembatalan.required' => 'Alasan penolakan / pembatalan wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $prestasi = PrestasiMahasiswa::where('id_prestasi', $id)->first();

            if (!$prestasi) {
                $skpi = SKPI::find($id);
                $prestasi = $skpi ? $skpi->prestasi : null;
            }

            if (!$prestasi) {
                return redirect()->back()->with('error', 'Data prestasi tidak ditemukan.');
            }

            // Status 99 = Dibatalkan / Ditolak Ditmawa
            PrestasiMahasiswa::where('id_aktivitas', $prestasi->id_aktivitas)->update([
                'approved'          => 99,
                'alasan_pembatalan' => $request->alasan_pembatalan
            ]);

            SKPI::where('id_prestasi', $prestasi->id_prestasi)->update([
                'approved' => 99
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Laporan prestasi telah ditolak / dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan prestasi: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Data Prestasi
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $prestasi = PrestasiMahasiswa::where('id_prestasi', $id)->first();

            if ($prestasi) {
                SKPI::where('id_prestasi', $id)->delete();
                $prestasi->delete();
            } else {
                $skpi = SKPI::find($id);
                if ($skpi) {
                    if ($skpi->prestasi) {
                        $skpi->prestasi->delete();
                    }
                    $skpi->delete();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data prestasi berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function get_mahasiswa(Request $request)
    {
        try {
            $search   = $request->get('q');
            $selected = $request->get('selected', []); // Array ID yang sudah dipilih

            $query = RiwayatPendidikan::with('prodi')
                ->whereNull('id_jenis_keluar');

            // Kecualikan mahasiswa yang sudah dipilih
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

            $query = PenugasanDosen::where('id_tahun_ajaran', optional($tahun_ajaran)->id_tahun_ajaran)
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
                    'text' => ($item->nidn ?? '-') . " - " . $item->nama_dosen . " (" . ($item->nama_program_studi ?? '-') . ")"
                ];
            }

            return response()->json(['results' => $results]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}