<?php

namespace App\Http\Controllers\Mahasiswa\Prestasi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use App\Models\Referensi\JenisPrestasi;
use App\Models\Referensi\TingkatPrestasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class PrestasiMahasiswaController extends Controller
{
    public function prestasi_mahasiswa()
    {
        // dd($semester_aktif->id_semester);
        $id_reg_mhs = auth()->user()->fk_id;
        $data_mahasiswa = RiwayatPendidikan::with('biodata')->where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

        $data = PrestasiMahasiswa::where('id_mahasiswa', $data_mahasiswa->biodata->id_mahasiswa)->whereNull('id_aktivitas')->get();
        // dd($data_mahasiswa->biodata->id_mahasiswa);

        return view('mahasiswa.prestasi.index', ['data' => $data]);
    }

    public function tambah_prestasi_mahasiswa()
    {
        // dd($semester_aktif->id_semester);
        $id_reg_mhs = auth()->user()->fk_id;
        $jenis_prestasi = JenisPrestasi::orderBy('id_jenis_prestasi')->get();
        $tingkat_prestasi = TingkatPrestasi::orderBy('id_tingkat_prestasi')->get();
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

        return view('mahasiswa.prestasi.store', ['data' => $data, 'tingkat_prestasi' => $tingkat_prestasi, 'jenis_prestasi' => $jenis_prestasi]);
    }

    public function store_prestasi_mahasiswa(Request $request)
    {
        $request->validate([
            'kategori_prestasi.*' => ['required', Rule::in(['1','2'])],
            'nama_prestasi.*' => 'required',
            'jenis_prestasi.*' => ['required', Rule::in(['1','2','3','9'])],
            'tingkat_prestasi.*' => ['required', Rule::in(['1','2','3','4','5','6','7','9'])],
            'tahun_prestasi.*' => 'required',
            'penyelenggara.*' => 'required',
            'file_prestasi' => 'required|file|mimes:pdf|max:500',
        ]);

        DB::beginTransaction();

        try {

            $id_reg_mhs = auth()->user()->fk_id;
            $mahasiswa = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

            if (!$mahasiswa) {
                throw new \Exception('Data mahasiswa tidak ditemukan');
            }

            // Generate ID prestasi
            $id_prestasi_mahasiswa = Uuid::uuid4()->toString();

            // Ambil NIM mahasiswa
            $nim = $mahasiswa->nim;

            // Upload file
            $file = $request->file('file_prestasi');
            $extension = $file->getClientOriginalExtension();

            // Format nama file baru
            $nama_file = 'prestasi_mahasiswa_'.$nim.'_'.$id_prestasi_mahasiswa.'.'.$extension;

            $path = $file->storeAs('prestasi_mahasiswa', $nama_file, 'public');

            $jenis_prestasi = JenisPrestasi::where('id_jenis_prestasi', $request->jenis_prestasi[0])->first();
            $tingkat_prestasi = TingkatPrestasi::where('id_tingkat_prestasi', $request->tingkat_prestasi[0])->first();

            PrestasiMahasiswa::create([
                'id_prestasi' => $id_prestasi_mahasiswa,
                'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                'nama_mahasiswa' => $mahasiswa->nama_mahasiswa,
                'kategori_prestasi' => $request->kategori_prestasi[0],
                'id_jenis_prestasi' => $jenis_prestasi->id_jenis_prestasi,
                'nama_jenis_prestasi' => $jenis_prestasi->nama_jenis_prestasi,
                'id_tingkat_prestasi' => $tingkat_prestasi->id_tingkat_prestasi,
                'nama_tingkat_prestasi' => $tingkat_prestasi->nama_tingkat_prestasi,
                'nama_prestasi' => $request->nama_prestasi[0],
                'tahun_prestasi' => $request->tahun_prestasi[0],
                'penyelenggara' => $request->penyelenggara[0],
                'file_prestasi' => $path
            ]);

            DB::commit();

            return redirect()
                ->route('mahasiswa.prestasi.index')
                ->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Exception $e) {

            DB::rollBack();

            // Hapus file jika sudah terupload
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan, data tidak tersimpan.');
        }
    }

    public function upload_file(Request $request, $id)
    {
        $request->validate([
            'file_prestasi' => 'required|file|mimes:pdf|max:500'
        ]);

        try {
            $prestasi = PrestasiMahasiswa::findOrFail($id);

            // Hapus file lama jika ada
            if ($prestasi->file_prestasi && 
                Storage::disk('public')->exists($prestasi->file_prestasi)) {
                Storage::disk('public')->delete($prestasi->file_prestasi);
            }

            $file = $request->file('file_prestasi');
            $nama_file = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('prestasi_mahasiswa', $nama_file, 'public');

            $prestasi->update([
                'file_prestasi' => $path
            ]);

            return back()->with('success', 'File berhasil diupload');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function edit($id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);
        // dd($prestasi);

        // 🔒 Tidak boleh edit jika sudah approved
        if ($prestasi->approved > 0) {
            return redirect()->route('mahasiswa.prestasi.index')
                ->with('error', 'Data yang sudah diverifikasi tidak dapat diedit.');
        }

        $jenis_prestasi = JenisPrestasi::orderBy('id_jenis_prestasi')->get();
        $tingkat_prestasi = TingkatPrestasi::orderBy('id_tingkat_prestasi')->get();

        return view('mahasiswa.prestasi.edit', compact(
            'prestasi',
            'jenis_prestasi',
            'tingkat_prestasi'
        ));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all(), $id);
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved > 0) {
            return redirect()->back()
                ->with('error', 'Data yang sudah diverifikasi tidak dapat diedit.');
        }

        $request->validate([
            'kategori_prestasi' => 'required|in:1,2',
            'nama_prestasi' => 'required',
            'jenis_prestasi' => 'required|in:1,2,3,9',
            'tingkat_prestasi' => 'required|in:1,2,3,4,5,6,7,9',
            'tahun_prestasi' => 'required',
            'penyelenggara' => 'required',
            'file_prestasi' => 'nullable|file|mimes:pdf|max:500',
        ]);

        DB::beginTransaction();

        try {

            $jenis = JenisPrestasi::findOrFail($request->jenis_prestasi);
            $tingkat = TingkatPrestasi::where('id_tingkat_prestasi', $request->tingkat_prestasi)->first();

            // Jika upload file baru
            if ($request->hasFile('file_prestasi')) {

                // Ambil nim mahasiswa (ambil dari relasi atau field)
                $nim = $prestasi->nim ?? auth()->user()->username ?? 'unknown';

                // Hapus file lama
                if ($prestasi->file_prestasi &&
                    Storage::disk('public')->exists($prestasi->file_prestasi)) {

                    Storage::disk('public')->delete($prestasi->file_prestasi);
                }

                $file = $request->file('file_prestasi');

                // Nama file baru
                $nama_file = 'prestasi_mahasiswa_' . $nim . '_' . $prestasi->id_prestasi . '.pdf';

                $path = $file->storeAs('prestasi_mahasiswa', $nama_file, 'public');

                $prestasi->file_prestasi = $path;
            }

            $prestasi->update([
                'kategori_prestasi' => $request->kategori_prestasi,
                'nama_prestasi' => $request->nama_prestasi,
                'id_jenis_prestasi' => $jenis->id_jenis_prestasi,
                'nama_jenis_prestasi' => $jenis->nama_jenis_prestasi,
                'id_tingkat_prestasi' => $tingkat->id_tingkat_prestasi,
                'nama_tingkat_prestasi' => $tingkat->nama_tingkat_prestasi,
                'tahun_prestasi' => $request->tahun_prestasi,
                'penyelenggara' => $request->penyelenggara,
            ]);

            DB::commit();

            return redirect()
                ->route('mahasiswa.prestasi.index')
                ->with('success', 'Data berhasil diperbarui.');

        } 
        catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat update data.');
        }
        // catch (\Exception $e) {

        //     DB::rollBack();

        //     dd([
        //         'message' => $e->getMessage(),
        //         'line' => $e->getLine(),
        //         'file' => $e->getFile(),
        //     ]);
        // }
    }

    public function delete_prestasi_mahasiswa($id)
    {
        DB::beginTransaction();

        try {

            $prestasi = PrestasiMahasiswa::findOrFail($id);

            // Tidak boleh hapus jika sudah approved
            if ($prestasi->approved > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Data yang sudah diverifikasi tidak dapat dihapus.');
            }

            // Hapus file jika ada
            if ($prestasi->file_prestasi && 
                Storage::disk('public')->exists($prestasi->file_prestasi)) {

                Storage::disk('public')->delete($prestasi->file_prestasi);
            }

            $prestasi->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Data berhasil dihapus.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
         
}
