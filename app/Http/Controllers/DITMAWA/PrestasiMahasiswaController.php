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
        'kategori_pendanaan'  => 'required',
        'id_tingkat_prestasi' => 'required',
        'kategori_prestasi'   => 'required',
        'nama_kompetisi'      => 'required|string',
        'nama_cabang'         => 'required|string',
        'peringkat'           => 'required',
        'penyelenggara'       => 'required|string',
        'jumlah_pt_peserta'   => 'required|numeric',
        'kepesertaan'         => 'required',
        'bentuk'              => 'required',
        'url_kompetisi'       => 'nullable|url',
        'file_sertifikat'     => 'required|file|mimes:pdf|max:500',
        'file_foto_upp'       => 'nullable|file|mimes:pdf|max:500',
        'file_undangan'       => 'nullable|file|mimes:pdf|max:500',
        'file_surat_tugas.*'  => 'nullable|file|mimes:pdf|max:500',
        'tanggal_sertifikat'  => 'required|date',
        'mahasiswa'           => 'required|array|min:1',
    ], [
        'kategori_prestasi.required' => 'Kategori prestasi wajib dipilih.',
        'file_sertifikat.max'        => 'Ukuran file sertifikat tidak boleh melebihi 500 KB.',
        'file_sertifikat.mimes'      => 'Format file sertifikat harus PDF.',
        'file_surat_tugas.*.max'     => 'Ukuran file surat tugas dosen tidak boleh melebihi 500 KB.',
        'file_surat_tugas.*.mimes'   => 'Format file surat tugas dosen harus PDF.',
    ]);

    DB::beginTransaction();
    try {
        $id_aktivitas = (string) Str::uuid();

        // ----------------------------------------------------
        // KONDISI DETERMINASI ID_SEMESTER DARI TANGGAL SERTIFIKAT
        // ----------------------------------------------------
        $tgl_sertifikat = \Carbon\Carbon::parse($request->tanggal_sertifikat);

        if ($tgl_sertifikat->month >= 1 && $tgl_sertifikat->month <= 7) {
            $calculated_id_semester = ($tgl_sertifikat->year - 1) . '2';
        } else {
            $calculated_id_semester = $tgl_sertifikat->year . '1';
        }

        // Ambil nama semester berdasarkan id_semester yang terbentuk
        $semester = Semester::where('id_semester', $calculated_id_semester)->first();

        // Fallback semester aktif jika semester dari kalkulasi tidak ditemukan di tabel semesters
        $semesterAktif = SemesterAktif::leftJoin('semesters', 'semesters.id_semester', 'semester_aktifs.id_semester')->first();

        // DIE DUMP UNTUK CEK KONDISI DATA SEMESTER
        // dd([
        //     'input_tanggal_sertifikat' => $request->tanggal_sertifikat,
        //     'parsed_month'             => $tgl_sertifikat->month,
        //     'parsed_year'              => $tgl_sertifikat->year,
        //     'calculated_id_semester'   => $calculated_id_semester,
        //     'query_semester_found'     => $semester ? $semester->toArray() : 'NOT FOUND IN DB',
        //     'semester_aktif_fallback'  => $semesterAktif ? $semesterAktif->toArray() : 'NULL',
        // ]);

        // Penentuan jenis_anggota untuk Aktivitas Mahasiswa
        $isIndividu = in_array(strtolower($request->kepesertaan), ['0', '1', 'individu', 'personal']);
        $nama_jenis_anggota = $request->kepesertaan== '0' ? 'Personal' : 'Kelompok';

        // Ambil data prodi dari mahasiswa pertama
        $firstMhs = RiwayatPendidikan::with('prodi')
            ->where('id_mahasiswa', $request->mahasiswa[0])
            ->orWhere('id_registrasi_mahasiswa', $request->mahasiswa[0])
            ->whereNull('id_jenis_keluar')
            ->first();

        $id_prodi_pertama   = $firstMhs ? $firstMhs->id_prodi : NULL;
        $nama_prodi_pertama = $firstMhs ? ($firstMhs->nama_program_studi ?? optional($firstMhs->prodi)->nama_program_studi) : NULL;

        // 1. Upload File Sertifikat, Foto UPP, Undangan, dan SK Pembimbing Dosen Pertama
        $path_sertifikat = $request->hasFile('file_sertifikat') 
            ? $request->file('file_sertifikat')->store('prestasi-mahasiswa/sertifikat', 'public') 
            : null;

        $path_foto_upp = $request->hasFile('file_foto_upp') 
            ? $request->file('file_foto_upp')->store('prestasi-mahasiswa/foto-upp', 'public') 
            : null;

        $path_undangan = $request->hasFile('file_undangan') 
            ? $request->file('file_undangan')->store('prestasi-mahasiswa/undangan', 'public') 
            : null;

        // File surat tugas dari dosen pertama (index 0)
        $path_sk_tugas_utama = null;
        if ($request->hasFile('file_surat_tugas.0')) {
            $path_sk_tugas_utama = $request->file('file_surat_tugas.0')
                ->store('prestasi-mahasiswa/sk-pembimbing', 'public');
        }

        // 2. Insert Aktivitas Mahasiswa
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
            'id_semester'               => $semester->id_semester,
            'nama_semester'             => $semester->nama_semester,
            'judul'                     => $request->nama_kompetisi,
            'keterangan'                => $request->keterangan,
            'lokasi'                    => $request->lokasi ?? $request->penyelenggara,
            'sk_tugas'                  => $path_sk_tugas_utama,
            'tanggal_sk_tugas'          => $request->tanggal_sertifikat,
            'untuk_kampus_merdeka'      => 0,
            'asal_data'                 => 9,
            'status_sync'               => 'belum sync'
        ];
        AktivitasMahasiswa::create($data_aktivitas);

        // 3 & 4. Insert Prestasi Mahasiswa & Anggota Aktivitas
        $totalMahasiswa = count($request->mahasiswa);
        $jenis_prestasi = JenisPrestasi::where('id_jenis_prestasi', $request->kategori_prestasi)->first();
        $tingkat_prestasi = TingkatPrestasi::where('id_tingkat_prestasi', $request->id_tingkat_prestasi)->first();

        foreach ($request->mahasiswa as $index => $id_mhs) {
            $riwayat = RiwayatPendidikan::where('id_mahasiswa', $id_mhs)
                ->orWhere('id_registrasi_mahasiswa', $id_mhs)
                ->whereNull('id_jenis_keluar')
                ->first();

            if ($riwayat) {
                // Insert Record Prestasi
                PrestasiMahasiswa::create([
                    'id_prestasi'           => (string) Str::uuid(),
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

        // 5. Insert Dosen Pembimbing ke bimbing_mahasiswas
        if (!empty($request->dosen_bimbing)) {
            foreach ($request->dosen_bimbing as $idx => $id_dosen) {
                if (!empty($id_dosen)) {
                    $dosen = PenugasanDosen::where('id_dosen', $id_dosen)->first();

                    $path_surat_tugas = null;
                    if ($idx === 0 && $path_sk_tugas_utama) {
                        $path_surat_tugas = $path_sk_tugas_utama;
                    } elseif ($request->hasFile("file_surat_tugas.{$idx}")) {
                        $path_surat_tugas = $request->file("file_surat_tugas.{$idx}")
                            ->store('prestasi-mahasiswa/SK Pembimbing', 'public');
                    }

                    BimbingMahasiswa::create([
                        'feeder'                 => 0,
                        'approved'               => 1,
                        'approved_dosen'         => 1,
                        'alasan_pembatalan'      => NULL,
                        'id_bimbing_mahasiswa'   => (string) Str::uuid(),
                        'id_aktivitas'           => $id_aktivitas,
                        'judul'                  => $request->nama_kompetisi,
                        'id_kategori_kegiatan'   => 110600,
                        'nama_kategori_kegiatan' => 'Membina kegiatan mahasiswa di bidang akademik dan kemahasiswaan, termasuk dalam kegiatan ini adalah membimbing mahasiswa menghasilkan produk saintifik, membimbing mahasiswa mengikuti kompetisi di bidang akademik dan kemahasiswaan',
                        'id_dosen'               => $id_dosen,
                        'nidn'                   => $dosen ? $dosen->nidn : NULL,
                        'nuptk'                  => $dosen ? $dosen->nuptk : NULL,
                        'nama_dosen'             => $dosen ? $dosen->nama_dosen : NULL,
                        'pembimbing_ke'          => $idx + 1,
                        'nilai_proses_bimbingan' => NULL,
                        'status_sync'            => 'belum sync',
                        // 'file_surat_tugas'       => $path_surat_tugas,
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