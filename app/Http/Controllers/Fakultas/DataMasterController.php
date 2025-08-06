<?php

namespace App\Http\Controllers\Fakultas;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\PenundaanBayar;
use Illuminate\Validation\Rule;
use App\Models\RuangPerkuliahan;
use App\Models\Connection\Tagihan;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\MatkulMerdeka;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\PrasyaratMatkul;
use App\Models\Perkuliahan\RencanaPembelajaran;

class DataMasterController extends Controller
{
    public function dosen()
    {
        $db = new BiodataDosen();
        $data = $db->get();

        return view('fakultas.data-master.dosen.index', [
            'data' => $data
        ]);
    }

    public function mahasiswa(Request $request)
    {
        $semesterAktif = SemesterAktif::first()->id_semester;

        $prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->get();


        $id_prodi_fak=$prodi_fak->pluck('id_prodi');

        // $query = RiwayatPendidikan::with(['kurikulum', 'pembimbing_akademik', 'beasiswa'])
        //     ->whereIn('id_prodi',  $id_prodi_fak)
        //     ->orderBy('nama_program_studi', 'ASC')
        //     // ->orderBy('id_jenjang_pendidikan', 'ASC')
        //     ->orderBy('id_periode_masuk', 'desc') // Pastikan orderBy di sini
        //     ->limit(10)
        //     ;

        // $data=$query->get();


        // foreach($data as $key => $value) {
        //     $value->rm_no_test = Registrasi::where('rm_nim', $value->nim)->pluck('rm_no_test')->first();

        //     $value->tagihan = Tagihan::with('pembayaran')
        //                 ->whereIn('tagihan.nomor_pembayaran', [$value->rm_no_test, $value->nim])
        //                 ->where('tagihan.kode_periode', $semesterAktif)
        //                 ->first();

        //     $value->penundaan_bayar = PenundaanBayar::where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)
        //                             ->where('id_semester', $semesterAktif)
        //                             ->first() ? 1 : 0;
        // }

        // dd($data);

        $angkatan = RiwayatPendidikan::with(['prodi'])
                    ->whereIn('id_prodi', $id_prodi_fak)
                    ->select(DB::raw('LEFT(id_periode_masuk, 4) as angkatan_raw'))
                    ->distinct()
                    ->orderBy('angkatan_raw', 'desc')
                    ->get();

        $status_keluar = RiwayatPendidikan::select('id_jenis_keluar', 'keterangan_keluar')
            ->where(function ($query) {
                $query->whereNotIn('id_jenis_keluar', ['C']) // Mengecualikan nilai 'C'
                    // ->orWhereNull('id_jenis_keluar')
                    ; // Menambahkan kondisi untuk menangani NULL
            })
            ->groupBy('id_jenis_keluar', 'keterangan_keluar')
            ->get();

        // Iterasi setiap item untuk memeriksa kondisi null
        // $status_keluar = $status_keluar->map(function ($item) {
        //     if (is_null($item->keterangan_keluar)) { // Periksa apakah null
        //         $item->id_jenis_keluar = '9';
        //         $item->keterangan_keluar = 'Aktif';
        //     }
        //     return $item;
        // });


        // dd($status_keluar);
        // dd($request->prodi, $request->angkatan, $request->status_keluar);

        $kurikulum = ListKurikulum::where('id_prodi', auth()->user()->fk_id)->where('is_active', 1)->get();

        $dosDb = new BiodataDosen();
        $dosen = $dosDb->get();

        return view('fakultas.data-master.mahasiswa.index', [
            'angkatan' => $angkatan,
            'prodi' => $prodi_fak,
            'kurikulum' => $kurikulum,
            'dosen' => $dosen,
            'status_keluar' => $status_keluar,
            // 'mahasiswa'=>$data
        ]);
    }

    public function mahasiswa_data(Request $request)
    {
        $searchValue = $request->input('search.value');
        $semesterAktif = SemesterAktif::first()->id_semester;

        $prodi = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
            ->orderBy('id_jenjang_pendidikan')
            ->orderBy('nama_program_studi')
            ->pluck('id_prodi');

        // Membangun query awal
        $query = RiwayatPendidikan::with(['kurikulum', 'pembimbing_akademik', 'beasiswa', 'beasiswa.jenis_beasiswa'])
            ->whereIn('id_prodi', $prodi)
            ->orderBy('nama_program_studi', 'ASC')
            ->orderBy('id_periode_masuk', 'desc');

            // Modifikasi hasil data setelah diambil

        // if ($request->has('status_keluar') && !empty($request->status_keluar)) {
        //     if(in_array('*', $request->status_keluar)){
        //         // hapus bintang dari aary status keluar,
        //         if(count($request->status_keluar) > 0){
        //             $query  ->whereNull('id_jenis_keluar')
        //                     ->whereIn('id_jenis_keluar', $request->status_keluar);
        //         }else{
        //             $query->whereNull('id_jenis_keluar');
        //         }
        //     }
        //     $query->whereIn('id_jenis_keluar', $request->status_keluar);
        // }

        if ($request->has('status_keluar') && !empty($request->status_keluar)) {
            $status_keluar = array_filter($request->status_keluar, function ($value) {
                return $value !== '*';
            });

            if (in_array('*', $request->status_keluar)) {
                if (count($status_keluar) > 0) {
                    $query->where(function ($q) use ($status_keluar) {
                        $q->whereNull('id_jenis_keluar')
                          ->orWhereIn('id_jenis_keluar', $status_keluar);
                    });
                } else {
                    $query->whereNull('id_jenis_keluar');
                }
            } else {
                $query->whereIn('id_jenis_keluar', $status_keluar);
            }
        }


        // Filter berdasarkan `searchValue` jika ada
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%');
            });
        }


        // Filter berdasarkan program studi
        if ($request->has('prodi') && !empty($request->prodi)) {
            $query->whereIn('id_prodi', $request->prodi);
        }

        // Filter berdasarkan angkatan
        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $query->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $request->angkatan);
        }

        // Filter berdasarkan status keluar
        if ($request->has('status_keluar') && !empty($request->status_keluar)) {
            $filter = $request->status_keluar;
            $query->where(function ($q) use ($filter) {
                $q->whereIn('id_jenis_keluar', $filter)
                ->orWhere(function ($subQuery) {
                    $subQuery->whereNull('id_jenis_keluar')
                            ->whereNull('keterangan_keluar');
                });
            });
        }

        // Eksekusi query untuk mendapatkan data
        $data = $query->get();

        // dd($data);

        $limit = $request->input('length');
        $offset = $request->input('start');

        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            $columns = ['angkatan', 'nim', 'nama_mahasiswa'];

            if ($columns[$orderColumn] == 'angkatan') {
                if ($orderDirection == 'asc') {
                    $data = $data->sortBy(function($item) {
                        return substr($item->id_periode_masuk, 0, 4);
                    })->values();
                } else {
                    $data = $data->sortByDesc(function($item) {
                        return substr($item->id_periode_masuk, 0, 4);
                    })->values();
                }
            } else {
                if ($orderDirection == 'asc') {
                    $data = $data->sortBy($columns[$orderColumn])->values();
                } else {
                    $data = $data->sortByDesc($columns[$orderColumn])->values();
                }
            }
        }


        $recordsFiltered = $data->count();

        $data = $data->slice($offset, $limit)->values();

        //BISA DIOPTIMALISASI DENGAN GUNAKAN WHEREIN DARI DATA(NIM) UNTUK PEMBAYARAN DAN REGISTRASI
        foreach($data as $key => $value) {
            $value->rm_no_test = Registrasi::where('rm_nim', $value->nim)->pluck('rm_no_test')->first();

            $value->tagihan = Tagihan::with('pembayaran')
                        ->whereIn('tagihan.nomor_pembayaran', [$value->rm_no_test, $value->nim])
                        ->where('tagihan.kode_periode', $semesterAktif)
                        ->first();

            $value->penundaan_bayar = PenundaanBayar::where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)
                                    ->where('id_semester', $semesterAktif)
                                    ->first() ? 1 : 0;
        }

        $recordsTotal = RiwayatPendidikan::whereIn('id_prodi', $prodi)->count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        // dd($data);

        return response()->json($response);
    }



    //BIAYA KULIAH
    public function biaya_kuliah(Request $request)
    {
        return view('fakultas.data-master.biaya-kuliah.devop');
    }

    public function ruang_perkuliahan()
    {
        $fakultas_id = auth()->user()->fk_id;
        $data = RuangPerkuliahan::where('fakultas_id',$fakultas_id)->get();

        // dd($data);

        return view('fakultas.data-master.ruang-perkuliahan.index', [
            'data' => $data
        ]);
    }

    public function ruang_perkuliahan_store(Request $request)
    {
        $fakultas_id = auth()->user()->fk_id;
        $data = $request->validate([
            'nama_ruang' => 'required',
            'kapasitas_ruang' => 'required',
            'lokasi' => [
                'required',
                Rule::unique('ruang_perkuliahans')->where(function ($query) use($request, $fakultas_id) {
                    return $query->where('nama_ruang', $request->nama_ruang)
                        ->where('lokasi', $request->lokasi)
                        ->where('fakultas_id', $fakultas_id);
                }),
            ],
        ], [
            'lokasi.unique' => 'Ruang dengan nama dan lokasi ini sudah ada di fakultas Anda. Silahkan melakukan lakukan pengecekan kembali.',
        ]);

        // dd($request->kapasitas_ruang);

        RuangPerkuliahan::create(['nama_ruang'=> $request->nama_ruang, 'lokasi'=> $request->lokasi, 'fakultas_id'=> $fakultas_id, 'kapasitas_ruang' => $request->kapasitas_ruang]);

        return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
    }

    public function ruang_perkuliahan_update(Request $request, RuangPerkuliahan $ruang_perkuliahan)
    {
        $fakultas_id = auth()->user()->fk_id;
        $data = $request->validate([
            'nama_ruang' => 'required',
            'kapasitas_ruang' => 'required',
            'lokasi' => [
                'required',
                Rule::unique('ruang_perkuliahans')->where(function ($query) use($request, $fakultas_id, $ruang_perkuliahan) {
                    return $query->where('nama_ruang', $request->nama_ruang)
                        ->where('lokasi', $request->lokasi)
                        ->where('fakultas_id', $fakultas_id)
                        ->whereNotIn('id', [$ruang_perkuliahan->id]);
                }),
            ],
        ], [
            'lokasi.unique' => 'Ruang dengan nama dan lokasi ini sudah ada di fakultas Anda. Silahkan melakukan lakukan pengecekan kembali.',
        ]);

        try {
            // Cek relasi kelas_kuliah
            $kelasExists = KelasKuliah::where('ruang_perkuliahan_id', $ruang_perkuliahan->id)->exists();

            // Jika ada kelas, lokasi tidak boleh diubah
            if ($kelasExists && $ruang_perkuliahan->lokasi !== $request->lokasi) {
            return redirect()->back()->with('error', 'Lokasi tidak dapat diubah karena ruang sudah digunakan pada kelas perkuliahan.');
            }

            $ruang_perkuliahan->update($data);

            return redirect()->back()->with('success', 'Data Berhasil di Rubah');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function ruang_perkuliahan_destroy(RuangPerkuliahan $ruang_perkuliahan)
    {
        try {
            // Optimalkan dengan menggunakan exists() untuk cek relasi
            $kelasExists = KelasKuliah::where('ruang_perkuliahan_id', $ruang_perkuliahan->id)->exists();

            if (!$kelasExists) {
                $ruang_perkuliahan->delete();
                return redirect()->back()->with('success', 'Data Berhasil di Hapus');
            } else {
                return redirect()->back()->with('error', 'Ruang tidak dapat dihapus karena masih digunakan pada kelas perkuliahan.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
