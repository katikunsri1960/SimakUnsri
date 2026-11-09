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

class PresmaController extends Controller
{
    /**
     * Tampilkan daftar kandidat dalam bentuk paslon.
     */
    public function index()
    {
        $kandidats = DB::table('presma_kandidats')
                    ->orderBy('no_urut', 'ASC')
                    ->get()
                    ->groupBy('no_urut');
        
        $data = [];
        foreach ($kandidats as $noUrut => $paslon) {
            $capresma = $paslon->firstWhere('posisi', 'capresma');
            $cawapresma = $paslon->firstWhere('posisi', 'cawapresma');
            
            $dataDukung = DB::table('presma_data_dukungs')
                ->where('no_urut', $noUrut)
                ->pluck('deskripsi', 'kategori');

            $data[] = (object) [
                'no_urut'    => $noUrut,
                'capresma'   => $capresma,
                'cawapresma' => $cawapresma,
                'visi'       => $dataDukung['visi'] ?? '-',
                'misi'       => $dataDukung['misi'] ?? '-',
                'foto'       => $dataDukung['foto'] ?? null,
                'video'      => $dataDukung['video'] ?? null,  // Link Video Kampanye
                'berkas'     => $dataDukung['berkas'] ?? null, // File Lampiran
            ];
        }

        // dd($data);

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
            $capresma = DB::table('riwayat_pendidikans')
                ->where('id_registrasi_mahasiswa', $request->id_registrasi_mahasiswa_capresma)
                ->first();

            $cawapresma = DB::table('riwayat_pendidikans')
                ->where('id_registrasi_mahasiswa', $request->id_registrasi_mahasiswa_cawapresma)
                ->first();

            if (!$capresma || !$cawapresma) {
                return redirect()->back()->withInput()->with('error', 'Data riwayat pendidikan mahasiswa tidak ditemukan!');
            }

            // 1. Insert Capresma & Cawapresma
            DB::table('presma_kandidats')->insert([
                [
                    'no_urut'                 => $request->no_urut,
                    'id_registrasi_mahasiswa' => $capresma->id_registrasi_mahasiswa,
                    'nim'                     => $capresma->nim,
                    'nama_mahasiswa'          => $capresma->nama_mahasiswa,
                    'posisi'                  => 'capresma',
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ],
                [
                    'no_urut'                 => $request->no_urut,
                    'id_registrasi_mahasiswa' => $cawapresma->id_registrasi_mahasiswa,
                    'nim'                     => $cawapresma->nim,
                    'nama_mahasiswa'          => $cawapresma->nama_mahasiswa,
                    'posisi'                  => 'cawapresma',
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ]
            ]);

            // 2. Insert Visi & Misi
            DB::table('presma_data_dukungs')->insert([
                [
                    'no_urut'    => $request->no_urut,
                    'kategori'   => 'visi',
                    'deskripsi'  => $request->visi,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'no_urut'    => $request->no_urut,
                    'kategori'   => 'misi',
                    'deskripsi'  => $request->misi,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // 3. Insert Link Video (jika diisi)
            if ($request->filled('video')) {
                DB::table('presma_data_dukungs')->insert([
                    'no_urut'    => $request->no_urut,
                    'kategori'   => 'video',
                    'deskripsi'  => $request->video,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 4. Upload & Insert Foto
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('kandidat/foto', 'public');
                DB::table('presma_data_dukungs')->insert([
                    'no_urut'    => $request->no_urut,
                    'kategori'   => 'foto',
                    'deskripsi'  => $fotoPath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 5. Upload & Insert Berkas Lampiran
            if ($request->hasFile('berkas_dukung')) {
                $berkasPath = $request->file('berkas_dukung')->store('kandidat/berkas', 'public');
                DB::table('presma_data_dukungs')->insert([
                    'no_urut'    => $request->no_urut,
                    'kategori'   => 'berkas',
                    'deskripsi'  => $berkasPath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('ditmawa.presiden-mahasiswa.index')
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
        $kandidatGroup = DB::table('presma_kandidats')
            ->where('no_urut', $no_urut)
            ->get();

        if ($kandidatGroup->isEmpty()) {
            return redirect()
                ->route('ditmawa.presiden-mahasiswa.index')
                ->with('error', 'Data kandidat tidak ditemukan!');
        }

        $capresma = $kandidatGroup->firstWhere('posisi', 'capresma');
        $cawapresma = $kandidatGroup->firstWhere('posisi', 'cawapresma');

        $dataDukung = DB::table('presma_data_dukungs')
            ->where('no_urut', $no_urut)
            ->pluck('deskripsi', 'kategori');

        return view('ditmawa.presiden-mahasiswa.edit', [
            'no_urut'    => $no_urut,
            'capresma'   => $capresma,
            'cawapresma' => $cawapresma,
            'visi'       => $dataDukung['visi'] ?? '',
            'misi'       => $dataDukung['misi'] ?? '',
            'foto'       => $dataDukung['foto'] ?? null,
            'video'      => $dataDukung['video'] ?? null,  // Link Video
            'berkas'     => $dataDukung['berkas'] ?? null, // File Lampiran
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
            $capresma = DB::table('riwayat_pendidikans')
                ->where('id_registrasi_mahasiswa', $request->id_registrasi_mahasiswa_capresma)
                ->first();

            $cawapresma = DB::table('riwayat_pendidikans')
                ->where('id_registrasi_mahasiswa', $request->id_registrasi_mahasiswa_cawapresma)
                ->first();

            if (!$capresma || !$cawapresma) {
                return redirect()->back()->withInput()->with('error', 'Data riwayat pendidikan mahasiswa tidak ditemukan!');
            }

            // 1. Re-insert Data Kandidat (Capresma & Cawapresma)
            DB::table('presma_kandidats')->where('no_urut', $no_urut)->delete();

            DB::table('presma_kandidats')->insert([
                [
                    'no_urut'                 => $request->no_urut,
                    'id_registrasi_mahasiswa' => $capresma->id_registrasi_mahasiswa,
                    'nim'                     => $capresma->nim,
                    'nama_mahasiswa'          => $capresma->nama_mahasiswa,
                    'posisi'                  => 'capresma',
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ],
                [
                    'no_urut'                 => $request->no_urut,
                    'id_registrasi_mahasiswa' => $cawapresma->id_registrasi_mahasiswa,
                    'nim'                     => $cawapresma->nim,
                    'nama_mahasiswa'          => $cawapresma->nama_mahasiswa,
                    'posisi'                  => 'cawapresma',
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ]
            ]);

            // 2. Update Visi & Misi
            DB::table('presma_data_dukungs')->updateOrInsert(
                ['no_urut' => $no_urut, 'kategori' => 'visi'],
                ['deskripsi' => $request->visi, 'no_urut' => $request->no_urut, 'updated_at' => now()]
            );

            DB::table('presma_data_dukungs')->updateOrInsert(
                ['no_urut' => $no_urut, 'kategori' => 'misi'],
                ['deskripsi' => $request->misi, 'no_urut' => $request->no_urut, 'updated_at' => now()]
            );

            // 3. Update Link Video (jika diisi)
            if ($request->filled('video')) {
                DB::table('presma_data_dukungs')->updateOrInsert(
                    ['no_urut' => $no_urut, 'kategori' => 'video'],
                    ['deskripsi' => $request->video, 'no_urut' => $request->no_urut, 'updated_at' => now()]
                );
            } else {
                DB::table('presma_data_dukungs')->where('no_urut', $no_urut)->where('kategori', 'video')->delete();
            }

            // 4. Update Foto Paslon (jika upload baru)
            if ($request->hasFile('foto')) {
                $fotoLama = DB::table('presma_data_dukungs')
                    ->where('no_urut', $no_urut)
                    ->where('kategori', 'foto')
                    ->first();

                if ($fotoLama && Storage::disk('public')->exists($fotoLama->deskripsi)) {
                    Storage::disk('public')->delete($fotoLama->deskripsi);
                }

                $fotoPath = $request->file('foto')->store('kandidat/foto', 'public');

                DB::table('presma_data_dukungs')->updateOrInsert(
                    ['no_urut' => $no_urut, 'kategori' => 'foto'],
                    ['deskripsi' => $fotoPath, 'no_urut' => $request->no_urut, 'updated_at' => now()]
                );
            }

            // 5. Update Berkas Lampiran (jika upload baru)
            if ($request->hasFile('berkas_dukung')) {
                $berkasLama = DB::table('presma_data_dukungs')
                    ->where('no_urut', $no_urut)
                    ->where('kategori', 'berkas')
                    ->first();

                if ($berkasLama && Storage::disk('public')->exists($berkasLama->deskripsi)) {
                    Storage::disk('public')->delete($berkasLama->deskripsi);
                }

                $berkasPath = $request->file('berkas_dukung')->store('kandidat/berkas', 'public');

                DB::table('presma_data_dukungs')->updateOrInsert(
                    ['no_urut' => $no_urut, 'kategori' => 'berkas'],
                    ['deskripsi' => $berkasPath, 'no_urut' => $request->no_urut, 'updated_at' => now()]
                );
            }

            // Jika nomor urut berubah, perbarui sisa record data dukung
            if ($no_urut != $request->no_urut) {
                DB::table('presma_data_dukungs')
                    ->where('no_urut', $no_urut)
                    ->update(['no_urut' => $request->no_urut]);
            }

            DB::commit();

            return redirect()
                ->route('ditmawa.presiden-mahasiswa.index')
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
        DB::beginTransaction();
        try {
            // Hapus file foto & berkas di storage
            $files = DB::table('presma_data_dukungs')
                ->where('no_urut', $no_urut)
                ->whereIn('kategori', ['foto', 'video'])
                ->pluck('deskripsi');

            foreach ($files as $filePath) {
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            // Hapus data dari kedua tabel berdasarkan no_urut
            DB::table('presma_data_dukungs')->where('no_urut', $no_urut)->delete();
            DB::table('presma_kandidats')->where('no_urut', $no_urut)->delete();

            DB::commit();

            return redirect()
                ->route('ditmawa.presiden-mahasiswa.index')
                ->with('success', 'Data kandidat berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
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
        $totalMahasiswa        = RiwayatPendidikan::count();
        $belumMemilih          = max(0, $totalMahasiswa - $totalVotes);
        $persentasePartisipasi = $totalMahasiswa > 0 ? round(($totalVotes / $totalMahasiswa) * 100, 2) : 0;

        return view('ditmawa.presiden-mahasiswa.hasil-vote', [
            'kandidat'              => $kandidat,
            'rekapFakultas'         => $rekapFakultas,
            'totalSuaraPerFakultas' => $totalSuaraPerFakultas,
            'totalVotes'            => $totalVotes,
            'totalMahasiswa'        => $totalMahasiswa,
            'belumMemilih'          => $belumMemilih,
            'persentasePartisipasi' => $persentasePartisipasi,
            'idSemesterAktif'       => $semester_aktif,
        ]);
    }
}