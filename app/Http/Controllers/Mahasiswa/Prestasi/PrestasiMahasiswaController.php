<?php

namespace App\Http\Controllers\Mahasiswa\Prestasi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use App\Models\Referensi\JenisPrestasi;
use App\Models\Referensi\TingkatPrestasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class PrestasiMahasiswaController extends Controller
{
    public function prestasi_mahasiswa_non_pendanaan()
    {
        // dd($semester_aktif->id_semester);
        $id_reg_mhs = auth()->user()->fk_id;
        $data_mahasiswa = RiwayatPendidikan::with('biodata')->where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

        $data = PrestasiMahasiswa::where('id_mahasiswa', $data_mahasiswa->biodata->id_mahasiswa)->whereNull('id_aktivitas')->get();
        // dd($data_mahasiswa->biodata->id_mahasiswa);

        return view('mahasiswa.prestasi.index-non-pendanaan', ['data' => $data]);
    }

    public function tambah_prestasi_mahasiswa_non_pendanaan()
    {
        // dd($semester_aktif->id_semester);
        $id_reg_mhs = auth()->user()->fk_id;
        $jenis_prestasi = JenisPrestasi::get();
        $tingkat_prestasi = TingkatPrestasi::get();
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

        return view('mahasiswa.prestasi.store-non-pendanaan', ['data' => $data, 'tingkat_prestasi' => $tingkat_prestasi, 'jenis_prestasi' => $jenis_prestasi]);
    }

    public function store_prestasi_mahasiswa_non_pendanaan(Request $request)
    {
         //Validate request data
         $data = $request->validate([
            'nama_prestasi.*' => 'required',
            'jenis_prestasi.*' => [
                'required',
                Rule::in(['1','2','3','9'])
            ],
            'tingkat_prestasi.*' => [
                'required',
                Rule::in(['1','2','3','4','5','6','7','9'])
            ],
            'tahun_prestasi.*' => 'required',
            'penyelenggara.*' => 'required',
        ]);

        try {
            // dd($request->all());
            //Define variable
            $id_reg_mhs = auth()->user()->fk_id;
            $mahasiswa = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

            // dd($data);

            $jumlah_prestasi = count($request->nama_prestasi);

            for ($i = 0; $i < $jumlah_prestasi; $i++) {
                //Generate id aktivitas mengajar
                $id_prestasi_mahasiswa = Uuid::uuid4()->toString();
                $jenis_prestasi = JenisPrestasi::where('id_jenis_prestasi', $request->jenis_prestasi[$i])->first();
                $tingkat_prestasi = TingkatPrestasi::where('id_tingkat_prestasi', $request->tingkat_prestasi[$i])->first();

                PrestasiMahasiswa::create([
                    'id_prestasi' => $id_prestasi_mahasiswa,
                    'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                    'nama_mahasiswa' => $mahasiswa->nama_mahasiswa,
                    'id_jenis_prestasi' => $jenis_prestasi->id_jenis_prestasi,
                    'nama_jenis_prestasi' => $jenis_prestasi->nama_jenis_prestasi,
                    'id_tingkat_prestasi' => $tingkat_prestasi->id_tingkat_prestasi,
                    'nama_tingkat_prestasi' => $tingkat_prestasi->nama_tingkat_prestasi,
                    'nama_prestasi' => $request->nama_prestasi[$i],
                    'tahun_prestasi' => $request->tahun_prestasi[$i],
                    'penyelenggara' => $request->penyelenggara[$i]
                ]);
            }
            return redirect()->route('mahasiswa.prestasi.prestasi-non-pendanaan')->with('success', 'Data Berhasil di Tambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete_prestasi_mahasiswa_non_pendanaan($id)
    {
        try {
            $prestasi = PrestasiMahasiswa::findOrFail($id);
            $prestasi->delete();

            return redirect()->route('mahasiswa.prestasi.prestasi-non-pendanaan')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
