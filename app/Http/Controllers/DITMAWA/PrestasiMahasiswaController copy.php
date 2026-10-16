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
        'file_sertifikat'       => 'required|file|mimes:pdf|max:500',
        'file_foto_upp'         => 'nullable|file|mimes:pdf|max:500',
        'file_undangan'         => 'nullable|file|mimes:pdf|max:500',
        'file_sk_pembimbing'    => 'nullable|file|mimes:pdf|max:500',
        'no_sk_pembimbing'      => 'nullable|string',
        'tanggal_sk_pembimbing' => 'nullable|date',
        'tanggal_sertifikat'    => 'required|date',
        'mahasiswa'             => 'required|array|min:1',
    ], [
        'kategori_prestasi.required'  => 'Kategori prestasi wajib dipilih.',
        'file_sertifikat.max'         => 'Ukuran file sertifikat tidak boleh melebihi 500 KB.',
        'file_sertifikat.mimes'       => 'Format file sertifikat harus PDF.',
        'file_sk_pembimbing.max'      => 'Ukuran file SK pembimbing dosen tidak boleh melebihi 500 KB.',
        'file_sk_pembimbing.mimes'    => 'Format file SK pembimbing dosen harus PDF.',
    ]);

    DB::beginTransaction();
    try {
        $id_aktivitas = (string) Str::uuid();
        $id_prestasi_utama = (string) Str::uuid(); // Menjadi ID rujukan penamaan file bukti

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

        // 1. UPLOAD FILE BUKTI (NAMA FILE MENGGUNAKAN ID_PRESTASI)
        $path_sertifikat = null;
        if ($request->hasFile('file_sertifikat')) {
            $ext = $request->file('file_sertifikat')->getClientOriginalExtension();
            $path_sertifikat = $request->file('file_sertifikat')
                ->storeAs('prestasi-mahasiswa/sertifikat', $id_prestasi_utama . '.' . $ext, 'public');
        }

        $path_foto_upp = null;
        if ($request->hasFile('file_foto_upp')) {
            $ext = $request->file('file_foto_upp')->getClientOriginalExtension();
            $path_foto_upp = $request->file('file_foto_upp')
                ->storeAs('prestasi-mahasiswa/foto-upp', $id_prestasi_utama . '.' . $ext, 'public');
        }

        $path_undangan = null;
        if ($request->hasFile('file_undangan')) {
            $ext = $request->file('file_undangan')->getClientOriginalExtension();
            $path_undangan = $request->file('file_undangan')
                ->storeAs('prestasi-mahasiswa/undangan', $id_prestasi_utama . '.' . $ext, 'public');
        }

        // UPLOAD FILE SK PEMBIMBING (NAMA FILE TETAP MEMAKAI NAMA ASLI)
        $path_sk_pembimbing = null;
        if ($request->hasFile('file_sk_pembimbing')) {
            $fileSk = $request->file('file_sk_pembimbing');
            $nama_file_sk_asli = $fileSk->getClientOriginalName();
            $path_sk_pembimbing = $fileSk->storeAs('prestasi-mahasiswa/sk-pembimbing', $nama_file_sk_asli, 'public');
        }

        // 2. INSERT AKTIVITAS MAHASISWA
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
            'sk_tugas'                  => $request->no_sk_pembimbing, // Disimpan Nomor SK Pembimbing
            'tanggal_sk_tugas'          => $request->tanggal_sk_pembimbing ?? $request->tanggal_sertifikat, // Disimpan Tanggal SK Pembimbing
            'untuk_kampus_merdeka'      => 0,
            'asal_data'                 => 9,
            'status_sync'               => 'belum sync'
        ];
        AktivitasMahasiswa::create($data_aktivitas);

        // 3 & 4. INSERT PRESTASI MAHASISWA & ANGGOTA AKTIVITAS
        $totalMahasiswa = count($request->mahasiswa);
        $jenis_prestasi = JenisPrestasi::where('id_jenis_prestasi', $request->kategori_prestasi)->first();
        $tingkat_prestasi = TingkatPrestasi::where('id_tingkat_prestasi', $request->id_tingkat_prestasi)->first();

        foreach ($request->mahasiswa as $index => $id_mhs) {
            $riwayat = RiwayatPendidikan::where('id_mahasiswa', $id_mhs)
                ->orWhere('id_registrasi_mahasiswa', $id_mhs)
                ->whereNull('id_jenis_keluar')
                ->first();

            if ($riwayat) {
                // Record pertama memakai $id_prestasi_utama, selanjutnya buat UUID baru
                $current_id_prestasi = ($index === 0) ? $id_prestasi_utama : (string) Str::uuid();

                PrestasiMahasiswa::create([
                    'id_prestasi'           => $current_id_prestasi,
                    'approved'              => 3,
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
                    'file_prestasi'         => $path_sertifikat,
                    'file_foto_upp'         => $path_foto_upp,
                    'file_undangan'         => $path_undangan,
                    'sk_pembimbing'         => $path_sk_pembimbing, // Path file SK Pembimbing
                    'simkatmawa'            => 0,
                    'pdunsri'               => 0,
                    'feeder'                => 0,
                    'status_sync'           => 'belum sync'
                ]);

                // Penentuan Jenis Peran Anggota
                if ($totalMahasiswa === 1 || $request->kepesertaan == '0') {
                    $jenis_peran = '3';
                    $nama_jenis_peran = 'Personal';
                } else {
                    $jenis_peran = ($index === 0) ? '1' : '2';
                    $nama_jenis_peran = ($index === 0) ? 'Ketua' : 'Anggota';
                }

                // Insert Anggota
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

        // 5. INSERT DOSEN PEMBIMBING KE BIMBING_MAHASISWAS
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
                        // 'sk_tugas'               => $request->no_sk_pembimbing, // Nomor SK Pembimbing
                        // 'file_surat_tugas'       => $path_sk_pembimbing,     // Path File SK Pembimbing
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

    public function get_mahasiswa(Request $request)
    {
        try {
            $search = $request->get('q');

            $query = RiwayatPendidikan::with('prodi')
                ->whereNull('id_jenis_keluar');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_mahasiswa', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
                });
            }

            $mahasiswa = $query->orderBy('nama_mahasiswa', 'asc')->limit(25)->get();

            $results = [];
            foreach ($mahasiswa as $item) {
                // Ambil nama prodi dari kolom langsung atau relasi prodi
                $namaProdi = $item->nama_program_studi ?? optional($item->prodi)->nama_program_studi ?? '-';

                $results[] = [
                    // Menggunakan id_registrasi_mahasiswa atau id_mahasiswa yang pasti terisi
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
            $search = $request->get('q');

            $tahun_ajaran = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                            ->first();

            $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->id_tahun_ajaran)
                                    ->orderby('nama_dosen', 'asc');

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