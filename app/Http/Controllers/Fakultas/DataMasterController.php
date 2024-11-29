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
                    ->orWhereNull('id_jenis_keluar')
                    ; // Menambahkan kondisi untuk menangani NULL
            })
            ->groupBy('id_jenis_keluar', 'keterangan_keluar')
            ->get();
        
        // Iterasi setiap item untuk memeriksa kondisi null
        $status_keluar = $status_keluar->map(function ($item) {
            if (is_null($item->keterangan_keluar)) { // Periksa apakah null
                // $item->id_jenis_keluar = '*';
                $item->keterangan_keluar = 'Aktif';
            }
            return $item;
        });
                
                
            
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

        $query = RiwayatPendidikan::with(['kurikulum', 'pembimbing_akademik', 'beasiswa', 'beasiswa.jenis_beasiswa'])
                    ->whereIn('id_prodi',  $prodi)
                    // ->where('nim', 'like', '%' . '07011182126001'. '%')
                    ->orderBy('nama_program_studi', 'ASC')
                    // ->orderBy('id_jenjang_pendidikan', 'ASC')
                    ->orderBy('id_periode_masuk', 'desc') // Pastikan orderBy di sini
                    // ->limit(10)
                    ;

        if ($searchValue) {
            $query = $query->where(function($q) use ($searchValue) {
                $q->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%');
            });
        }

        if ($request->has('prodi') && !empty($request->prodi)) {
            $filter = $request->prodi;
            $query->whereIn('id_prodi', $filter);
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $query->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $filter);
        }

        $data=$query->get();
        
        // Gunakan map untuk memodifikasi hasil setelah data diambil
        $data = $data->map(function ($item) {
            if (is_null($item->keterangan_keluar)) { // Periksa apakah null
                // Tetapkan nilai default
                $item->keterangan_keluar = 'Aktif';
            }
            return $item;
        });
        
        
        $limit = $request->input('length');
        $offset = $request->input('start');

        
        //    dd($data);

        if ($request->has('status_keluar') && !empty($request->status_keluar)) {
            $filter = $request->status_keluar;
        
            $query->whereIn('keterangan_keluar', $filter);
            // $query->where(function ($q) use ($filter) {
            //     $q->whereIn('id_jenis_keluar', $filter)
            //     //   ->orWhereNull('keterangan_keluar')
            //     ; // Tambahkan kondisi untuk nilai NULL
            // });
        }

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
}
