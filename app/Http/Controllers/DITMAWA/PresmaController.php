<?php

namespace App\Http\Controllers\DITMAWA;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\SemesterAktif;
use App\Models\PresmaVote;
use App\Models\PresmaKandidat;
use App\Models\PresmaDataDukung;

class PresmaController extends Controller
{
    /**
     * Tampilkan daftar kandidat dalam bentuk paslon.
     */
    public function index()
    {
        // Eager load relasi dataDukung pada kandidat
        $kandidatsRaw = PresmaKandidat::with('dataDukung')
            ->orderBy('no_urut', 'ASC')
            ->get()
            ->groupBy('no_urut');
        
        $data = [];
        foreach ($kandidatsRaw as $noUrut => $paslon) {
            $capresma   = $paslon->firstWhere('posisi', 'capresma');
            $cawapresma = $paslon->firstWhere('posisi', 'cawapresma');
            
            // Ambil data dukung dari relasi yang sudah di-eager-load
            $firstKandidat = $paslon->first();
            $dataDukungMap = $firstKandidat ? $firstKandidat->dataDukung->pluck('deskripsi', 'kategori') : collect();

            $data[] = (object) [
                'no_urut'    => $noUrut,
                'capresma'   => $capresma,
                'cawapresma' => $cawapresma,
                'visi'       => $dataDukungMap['visi'] ?? '-',
                'misi'       => $dataDukungMap['misi'] ?? '-',
                'foto'       => $dataDukungMap['foto'] ?? null,
                'video'      => $dataDukungMap['video'] ?? null,  // Link Video Kampanye
                'berkas'     => $dataDukungMap['berkas'] ?? null, // File Lampiran
            ];
        }

        return view('ditmawa.presiden-mahasiswa.index', ['kandidat' => collect($data)]);
    }

    public function create()
    {
        return view('ditmawa.presiden-mahasiswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_urut'                            => 'required|numeric|unique:presma_kandidats,no_urut',
            'id_registrasi_mahasiswa_capresma'   => 'required|string|max:255',
            'id_registrasi_mahasiswa_cawapresma' => 'required|string|max:255',
            'visi'                               => 'required|string',
            'misi'                               => 'required|string',
            'video'                              => 'nullable|url|max:255',
            'foto'                               => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'berkas_dukung'                      => 'required|file|mimes:pdf,zip,rar|max:5048',
        ]);

        DB::beginTransaction();
        try {
            // Cari data riwayat pendidikan mahasiswa
            $capresma = RiwayatPendidikan::where('id_registrasi_mahasiswa', $request->id_registrasi_mahasiswa_capresma)->first();
            $cawapresma = RiwayatPendidikan::where('id_registrasi_mahasiswa', $request->id_registrasi_mahasiswa_cawapresma)->first();

            if (!$capresma || !$cawapresma) {
                return redirect()->back()->withInput()->with('error', 'Data riwayat pendidikan mahasiswa tidak ditemukan!');
            }

            // 1. Insert Capresma & Cawapresma menggunakan Eloquent Model
            PresmaKandidat::create([
                'no_urut'                 => $request->no_urut,
                'id_registrasi_mahasiswa' => $capresma->id_registrasi_mahasiswa,
                'nim'                     => $capresma->nim,
                'nama_mahasiswa'          => $capresma->nama_mahasiswa,
                'posisi'                  => 'capresma',
            ]);

            PresmaKandidat::create([
                'no_urut'                 => $request->no_urut,
                'id_registrasi_mahasiswa' => $cawapresma->id_registrasi_mahasiswa,
                'nim'                     => $cawapresma->nim,
                'nama_mahasiswa'          => $cawapresma->nama_mahasiswa,
                'posisi'                  => 'cawapresma',
            ]);

            // 2. Insert Visi & Misi
            PresmaDataDukung::create([
                'no_urut'   => $request->no_urut,
                'kategori'  => 'visi',
                'deskripsi' => $request->visi,
            ]);

            PresmaDataDukung::create([
                'no_urut'   => $request->no_urut,
                'kategori'  => 'misi',
                'deskripsi' => $request->misi,
            ]);

            // 3. Insert Link Video (jika diisi)
            if ($request->filled('video')) {
                PresmaDataDukung::create([
                    'no_urut'   => $request->no_urut,
                    'kategori'  => 'video',
                    'deskripsi' => $request->video,
                ]);
            }

            // 4. Upload & Insert Foto
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('kandidat/foto', 'public');
                PresmaDataDukung::create([
                    'no_urut'   => $request->no_urut,
                    'kategori'  => 'foto',
                    'deskripsi' => $fotoPath,
                ]);
            }

            // 5. Upload & Insert Berkas Lampiran
            if ($request->hasFile('berkas_dukung')) {
                $berkasPath = $request->file('berkas_dukung')->store('kandidat/berkas', 'public');
                PresmaDataDukung::create([
                    'no_urut'   => $request->no_urut,
                    'kategori'  => 'berkas',
                    'deskripsi' => $berkasPath,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('ditmawa.presiden-mahasiswa.kandidat.index')
                ->with('success', 'Data kandidat berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form edit kandidat berdasarkan no_urut.
     */
    public function edit($no_urut)
    {
        // Eager load dataDukung kandidat
        $kandidatGroup = PresmaKandidat::with('dataDukung')
            ->where('no_urut', $no_urut)
            ->get();

        if ($kandidatGroup->isEmpty()) {
            return redirect()
                ->route('ditmawa.presiden-mahasiswa.kandidat.index')
                ->with('error', 'Data kandidat tidak ditemukan!');
        }

        $capresma   = $kandidatGroup->firstWhere('posisi', 'capresma');
        $cawapresma = $kandidatGroup->firstWhere('posisi', 'cawapresma');

        $firstKandidat = $kandidatGroup->first();
        $dataDukungMap = $firstKandidat ? $firstKandidat->dataDukung->pluck('deskripsi', 'kategori') : collect();

        return view('ditmawa.presiden-mahasiswa.edit', [
            'no_urut'    => $no_urut,
            'capresma'   => $capresma,
            'cawapresma' => $cawapresma,
            'visi'       => $dataDukungMap['visi'] ?? '',
            'misi'       => $dataDukungMap['misi'] ?? '',
            'foto'       => $dataDukungMap['foto'] ?? null,
            'video'      => $dataDukungMap['video'] ?? null,  // Link Video
            'berkas'     => $dataDukungMap['berkas'] ?? null, // File Lampiran
        ]);
    }

    /**
     * Update data kandidat berdasarkan no_urut.
     */
    public function update(Request $request, $no_urut)
    {
        $request->validate([
            'no_urut'                            => 'required|numeric|unique:presma_kandidats,no_urut,' . $no_urut . ',no_urut',
            'id_registrasi_mahasiswa_capresma'   => 'required|string|max:255',
            'id_registrasi_mahasiswa_cawapresma' => 'required|string|max:255',
            'visi'                               => 'required|string',
            'misi'                               => 'required|string',
            'video'                              => 'nullable|url|max:255',
            'foto'                               => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'berkas_dukung'                      => 'nullable|file|mimes:pdf,zip,rar|max:5048',
        ]);

        DB::beginTransaction();
        try {
            $capresma = RiwayatPendidikan::where('id_registrasi_mahasiswa', $request->id_registrasi_mahasiswa_capresma)->first();
            $cawapresma = RiwayatPendidikan::where('id_registrasi_mahasiswa', $request->id_registrasi_mahasiswa_cawapresma)->first();

            if (!$capresma || !$cawapresma) {
                return redirect()->back()->withInput()->with('error', 'Data riwayat pendidikan mahasiswa tidak ditemukan!');
            }

            // 1. Re-insert Data Kandidat (Capresma & Cawapresma)
            PresmaKandidat::where('no_urut', $no_urut)->delete();

            PresmaKandidat::create([
                'no_urut'                 => $request->no_urut,
                'id_registrasi_mahasiswa' => $capresma->id_registrasi_mahasiswa,
                'nim'                     => $capresma->nim,
                'nama_mahasiswa'          => $capresma->nama_mahasiswa,
                'posisi'                  => 'capresma',
            ]);

            PresmaKandidat::create([
                'no_urut'                 => $request->no_urut,
                'id_registrasi_mahasiswa' => $cawapresma->id_registrasi_mahasiswa,
                'nim'                     => $cawapresma->nim,
                'nama_mahasiswa'          => $cawapresma->nama_mahasiswa,
                'posisi'                  => 'cawapresma',
            ]);

            // 2. Update Visi & Misi
            PresmaDataDukung::updateOrCreate(
                ['no_urut' => $no_urut, 'kategori' => 'visi'],
                ['deskripsi' => $request->visi, 'no_urut' => $request->no_urut]
            );

            PresmaDataDukung::updateOrCreate(
                ['no_urut' => $no_urut, 'kategori' => 'misi'],
                ['deskripsi' => $request->misi, 'no_urut' => $request->no_urut]
            );

            // 3. Update Link Video
            if ($request->filled('video')) {
                PresmaDataDukung::updateOrCreate(
                    ['no_urut' => $no_urut, 'kategori' => 'video'],
                    ['deskripsi' => $request->video, 'no_urut' => $request->no_urut]
                );
            } else {
                PresmaDataDukung::where('no_urut', $no_urut)->where('kategori', 'video')->delete();
            }

            // 4. Update Foto Paslon (jika upload baru)
            if ($request->hasFile('foto')) {
                $fotoLama = PresmaDataDukung::where('no_urut', $no_urut)->where('kategori', 'foto')->first();

                if ($fotoLama && Storage::disk('public')->exists($fotoLama->deskripsi)) {
                    Storage::disk('public')->delete($fotoLama->deskripsi);
                }

                $fotoPath = $request->file('foto')->store('kandidat/foto', 'public');

                PresmaDataDukung::updateOrCreate(
                    ['no_urut' => $no_urut, 'kategori' => 'foto'],
                    ['deskripsi' => $fotoPath, 'no_urut' => $request->no_urut]
                );
            }

            // 5. Update Berkas Lampiran (jika upload baru)
            if ($request->hasFile('berkas_dukung')) {
                $berkasLama = PresmaDataDukung::where('no_urut', $no_urut)->where('kategori', 'berkas')->first();

                if ($berkasLama && Storage::disk('public')->exists($berkasLama->deskripsi)) {
                    Storage::disk('public')->delete($berkasLama->deskripsi);
                }

                $berkasPath = $request->file('berkas_dukung')->store('kandidat/berkas', 'public');

                PresmaDataDukung::updateOrCreate(
                    ['no_urut' => $no_urut, 'kategori' => 'berkas'],
                    ['deskripsi' => $berkasPath, 'no_urut' => $request->no_urut]
                );
            }

            // Jika nomor urut berubah, perbarui sisa record data dukung
            if ($no_urut != $request->no_urut) {
                PresmaDataDukung::where('no_urut', $no_urut)
                    ->update(['no_urut' => $request->no_urut]);
            }

            DB::commit();

            return redirect()
                ->route('ditmawa.presiden-mahasiswa.kandidat.index')
                ->with('success', 'Data kandidat berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($no_urut)
    {
        $semester_aktif = SemesterAktif::first()->id_semester;

        // 1. Cek apakah kandidat no_urut ini sudah memiliki suara pada semester aktif
        $hasVotes = PresmaVote::where('no_urut', $no_urut)
            ->where('id_semester', $semester_aktif)
            ->exists();

        if ($hasVotes) {
            return redirect()
                ->back()
                ->with('error', 'Kandidat Paslon No. Urut ' . $no_urut . ' tidak dapat dihapus karena sudah memiliki suara masuk di semester ini!');
        }

        DB::beginTransaction();
        try {
            // 2. Hapus file foto & berkas di storage via Model Eloquent
            $files = PresmaDataDukung::where('no_urut', $no_urut)
                ->whereIn('kategori', ['foto', 'berkas'])
                ->pluck('deskripsi');

            foreach ($files as $filePath) {
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            // 3. Hapus data dari presma_data_dukungs & presma_kandidats
            PresmaDataDukung::where('no_urut', $no_urut)->delete();
            PresmaKandidat::where('no_urut', $no_urut)->delete();

            DB::commit();

            return redirect()
                ->route('ditmawa.presiden-mahasiswa.kandidat.index')
                ->with('success', 'Data kandidat berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data kandidat: ' . $e->getMessage());
        }
    }

    public function hasilVote()
    {
        $semester_aktif = SemesterAktif::first()->id_semester;

        // 1. Eager Loading presma_votes -> riwayatPendidikan -> prodi -> fakultas
        $votes = PresmaVote::with(['riwayatPendidikan.prodi.fakultas'])
            ->where('id_semester', $semester_aktif)
            ->get();

        $totalVotes = $votes->count();

        // 2. Ambil Kandidat beserta Eager Load Data Dukung (visi, misi, foto, dll)
        $kandidatsRaw = PresmaKandidat::with('dataDukung')->get()->groupBy('no_urut');
        $votesByNoUrut = $votes->groupBy('no_urut');

        $dataPaslon = [];
        foreach ($kandidatsRaw as $noUrut => $paslon) {
            $capresma   = $paslon->firstWhere('posisi', 'capresma');
            $cawapresma = $paslon->firstWhere('posisi', 'cawapresma');

            // Ambil data dukung dari relasi eager load
            $firstKandidat = $paslon->first();
            $dataDukungMap = $firstKandidat ? $firstKandidat->dataDukung->pluck('deskripsi', 'kategori') : collect();

            $jumlahSuara = isset($votesByNoUrut[$noUrut]) ? $votesByNoUrut[$noUrut]->count() : 0;
            $persentase  = $totalVotes > 0 ? round(($jumlahSuara / $totalVotes) * 100, 2) : 0;

            $dataPaslon[] = (object) [
                'no_urut'      => $noUrut,
                'capresma'     => $capresma,
                'cawapresma'   => $cawapresma,
                'foto'         => $dataDukungMap['foto'] ?? null,
                'visi'         => $dataDukungMap['visi'] ?? '-',
                'misi'         => $dataDukungMap['misi'] ?? '-',
                'jumlah_suara' => $jumlahSuara,
                'persentase'   => $persentase,
            ];
        }
        $kandidat = collect($dataPaslon)->sortBy('no_urut');

        // 3. Rekap Per Fakultas via Eager Loaded Collection (In-Memory Processing)
        $votesByFakultas = $votes->groupBy(function ($vote) {
            return optional(optional(optional($vote->riwayatPendidikan)->prodi)->fakultas)->nama_fakultas ?? 'Fakultas Lainnya';
        });

        $rekapFakultas = collect();
        $totalSuaraPerFakultas = [];

        foreach ($votesByFakultas as $namaFakultas => $fakultasVotes) {
            $totalSuaraPerFakultas[$namaFakultas] = $fakultasVotes->count();

            $perPaslon = $fakultasVotes->groupBy('no_urut')->map(function ($items, $noUrut) {
                return (object) [
                    'no_urut'            => $noUrut,
                    'total_suara_paslon' => $items->count(),
                ];
            })->values();

            $rekapFakultas->put($namaFakultas, $perPaslon);
        }

        // 4. Statistik Partisipasi Mahasiswa
        $totalMahasiswa        = RiwayatPendidikan::where('id_jenis_keluar', NULL)
                                ->count();

        $belumMemilih          = max(0, $totalMahasiswa - $totalVotes);
        $persentasePartisipasi = $totalMahasiswa > 0 ? round(($totalVotes / $totalMahasiswa) * 100, 2) : 0;

        // Hitung Total Hak Suara (Mahasiswa Aktif) Per Fakultas
        $totalHakSuaraPerFakultas = RiwayatPendidikan::with('prodi.fakultas')
            ->whereNull('id_jenis_keluar')
            ->get()
            ->groupBy(function ($mhs) {
                return optional(optional($mhs->prodi)->fakultas)->nama_fakultas ?? 'Fakultas Lainnya';
            })
            ->map(function ($items) {
                return $items->count();
            });

        return view('ditmawa.presiden-mahasiswa.hasil-vote', [
            'kandidat'                 => $kandidat,
            'rekapFakultas'            => $rekapFakultas,
            'totalSuaraPerFakultas'    => $totalSuaraPerFakultas,
            'totalHakSuaraPerFakultas' => $totalHakSuaraPerFakultas, // <-- Tambahkan ini
            'totalVotes'               => $totalVotes,
            'totalMahasiswa'           => $totalMahasiswa,
            'belumMemilih'             => $belumMemilih,
            'persentasePartisipasi'    => $persentasePartisipasi,
            'idSemesterAktif'          => $semester_aktif,
        ]);
    }
}