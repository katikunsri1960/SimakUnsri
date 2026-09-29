<?php

namespace App\Http\Controllers\Mahasiswa\PrestasiSKPI;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use App\Models\Referensi\JenisPrestasi;
use App\Models\Referensi\TingkatPrestasi;
use App\Models\SKPI; // Adjust namespace if necessary
use App\Models\SKPIBidangKegiatan; // Adjust namespace if necessary
use App\Models\SKPIJenisKegiatan;
use App\Models\SKPISubBidangKegiatan; // Adjust namespace if necessary

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class PrestasiMahasiswaController extends Controller
{
    public function prestasi_mahasiswa()
    {
        $id_reg_mhs = auth()->user()->fk_id;

        $data = SKPI::with(['jenisSkpi', 'jenisSkpi.bidang', 'jenisSkpi.subBidang'])
            ->where('id_registrasi_mahasiswa', $id_reg_mhs)
            ->whereHas('jenisSkpi', function ($query) {
                // Filter Bidang D (ID: 4) dan Sub Bidang Kompetisi (ID: 1 - sesuaikan ID ini)
                $query->where('bidang_id', 4)
                    ->where('sub_bidang_id', 1); 
            })
            ->get();

        return view('mahasiswa.prestasi-skpi.index', compact('data'));
    }

    public function tambah_prestasi_mahasiswa()
    {
        $id_reg_mhs = auth()->user()->fk_id;

        // Mengambil array pasangan [id => nama_jenis] yang unik khusus sub_bidang_id = 1
        $jenis_prestasi = SKPIJenisKegiatan::where('sub_bidang_id', 1)
            ->orderBy('id', 'ASC')
            ->pluck('nama_jenis', 'id')
            ->unique();

        $tingkat_prestasi = TingkatPrestasi::orderBy('id_tingkat_prestasi')->get();
        $bidang = SKPIBidangKegiatan::all();
        $sub_bidang = SKPISubBidangKegiatan::all();
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

        return view('mahasiswa.prestasi-skpi.create', compact(
            'data',
            'tingkat_prestasi',
            'jenis_prestasi',
            'bidang',
            'sub_bidang'
        ));
    }

    public function store(Request $request) {
        $request->validate([
            'kategori_prestasi' => 'required|in:1,2',
            'nama_prestasi'     => 'required|string|max:255',
            'jenis_prestasi'    => 'required|exists:skpi_jenis_kegiatan,id',
            'tingkat_prestasi'  => 'required|exists:tingkat_prestasi,id_tingkat_prestasi',
            'tahun_prestasi'    => 'required|digits:4|numeric|min:2000|max:' . date('Y'),
            'penyelenggara'     => 'required|string|max:255',
            'file_prestasi'     => 'required|mimes:pdf|max:500', // Max 500 KB
        ], [
            'file_prestasi.max'   => 'Ukuran file sertifikat maksimal 500 KB.',
            'file_prestasi.mimes' => 'Format sertifikat harus berupa file PDF.',
        ]);

        // 2. Ambil Data Mahasiswa & Referensi Terkait
        $id_reg_mhs = auth()->user()->fk_id;
        $riwayat    = RiwayatPendidikan::where('id_registrasi_mahasiswa',$id_reg_mhs)->firstOrFail();

        // Data Referensi Jenis SKPI & Tingkat Prestasi untuk mengambil teks 'nama'
        $jenis = SKPIJenisKegiatan::findOrFail($request->jenis_prestasi);
        $tingkat = TingkatPrestasi::where('id_tingkat_prestasi',$request->tingkat_prestasi)->first();

        // 3. Process Upload File
        $filePath = null;
        if ($request->hasFile('file_prestasi')) {
            $file =$request->file('file_prestasi');
            $fileName = 'prestasi_' . time() . '_' . $riwayat->nim . '.' . $file->getClientOriginalExtension();$filePath = $file->storeAs('uploads/prestasi',$fileName, 'public');
        }

        // 4. Eksekusi Transaction Simpan Dua Tabel
        try {
            DB::transaction(function () use ($request, $riwayat,$jenis, $tingkat,$filePath) {

                // A. Simpan ke tabel `prestasi_mahasiswa`
                PrestasiMahasiswa::create([
                    'approved'              => 0,
                    'id_mahasiswa'          => $riwayat->id_mahasiswa,
                    'nama_mahasiswa'        => $riwayat->nama_mahasiswa,
                    'kategori_prestasi'     => $request->kategori_prestasi,
                    'id_jenis_prestasi'     => $jenis->id,
                    'nama_jenis_prestasi'   => $jenis->nama_jenis,
                    'id_tingkat_prestasi'   => $tingkat ? $tingkat->id_tingkat_prestasi : null,
                    'nama_tingkat_prestasi' => $tingkat ? $tingkat->nama_tingkat_prestasi : null,
                    'nama_prestasi'         => $request->nama_prestasi,
                    'tahun_prestasi'        => $request->tahun_prestasi,
                    'penyelenggara'         => $request->penyelenggara,
                    'file_prestasi'         => $filePath,
                ]);

                // B. Simpan ke tabel `skpi_data`
                SKPI::create([
                    'id_registrasi_mahasiswa' => $riwayat->id_registrasi_mahasiswa,
                    'id_prodi'                => $riwayat->id_prodi,
                    'id_semester'             => $riwayat->id_semester ?? $riwayat->id_periode_masuk,
                    'nama_kegiatan'           => $request->nama_prestasi,
                    'tahun'                   => $request->tahun_prestasi,
                    'id_jenis_skpi'           => $jenis->id,
                    'nama_jenis_skpi'         => $jenis->nama_jenis,
                    'skor'                    => $jenis->skor ?? 0,
                    'file_pendukung'          => $filePath,
                    'approved'                => 0,
                ]);
            });

            return redirect()->route('mahasiswa.prestasi-skpi.index')
                ->with('success', 'Data prestasi dan SKPI berhasil disimpan!');

        } catch (\Exception $e) {
            // Hapus file jika query database gagal
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function upload_file(Request $request, $id)
    {
        $request->validate([
            'file_prestasi' => 'required|file|mimes:pdf|max:500'
        ]);

        try {
            $prestasi = PrestasiMahasiswa::findOrFail($id);

            if ($prestasi->file_prestasi && Storage::disk('public')->exists($prestasi->file_prestasi)) {
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
            return back()->with('error', 'Terjadi kesalahan saat mengupload file.');
        }
    }

    public function edit($id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved > 0) {
            return redirect()->route('mahasiswa.prestasi-skpi.index')
                ->with('error', 'Data yang sudah diverifikasi tidak dapat diedit.');
        }

        $jenis_prestasi = JenisPrestasi::orderBy('id_jenis_prestasi')->get();
        $tingkat_prestasi = TingkatPrestasi::orderBy('id_tingkat_prestasi')->get();
        $bidang = SKPIBidangKegiatan::all();
        $sub_bidang = SKPISubBidangKegiatan::all();

        return view('mahasiswa.prestasi-skpi.edit', compact(
            'prestasi',
            'jenis_prestasi',
            'tingkat_prestasi',
            'bidang',
            'sub_bidang'
        ));
    }

    public function update(Request $request, $id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved > 0) {
            return redirect()->back()
                ->with('error', 'Data yang sudah diverifikasi tidak dapat diedit.');
        }

        $request->validate([
            'kategori_prestasi' => 'required|in:1,2',
            'nama_prestasi' => 'required|string|max:255',
            'bidang_id' => 'required',
            'sub_bidang_id' => 'required_if:bidang_id,4|nullable',
            'jenis_prestasi' => 'required',
            'tingkat_prestasi' => 'required',
            'tahun_prestasi' => 'required|numeric',
            'penyelenggara' => 'required|string|max:255',
            'file_prestasi' => 'nullable|file|mimes:pdf|max:500',
        ], [
            'sub_bidang_id.required_if' => 'Sub Bidang wajib dipilih jika Bidang yang dipilih adalah Bidang D!'
        ]);

        DB::beginTransaction();

        try {
            $jenis = JenisPrestasi::where('id_jenis_prestasi', $request->jenis_prestasi)->first();
            $tingkat = TingkatPrestasi::where('id_tingkat_prestasi', $request->tingkat_prestasi)->first();

            if ($request->hasFile('file_prestasi')) {
                $nim = $prestasi->nim ?? auth()->user()->username ?? 'unknown';

                if ($prestasi->file_prestasi && Storage::disk('public')->exists($prestasi->file_prestasi)) {
                    Storage::disk('public')->delete($prestasi->file_prestasi);
                }

                $file = $request->file('file_prestasi');
                $nama_file = 'prestasi_mahasiswa_' . $nim . '_' . $prestasi->id_prestasi . '.pdf';
                $path = $file->storeAs('prestasi_mahasiswa', $nama_file, 'public');

                $prestasi->file_prestasi = $path;
            }

            $prestasi->update([
                'kategori_prestasi' => $request->kategori_prestasi,
                'nama_prestasi' => $request->nama_prestasi,
                'bidang_id' => $request->bidang_id,
                'sub_bidang_id' => $request->bidang_id == 4 ? $request->sub_bidang_id : null,
                'id_jenis_prestasi' => $jenis->id_jenis_prestasi ?? $request->jenis_prestasi,
                'nama_jenis_prestasi' => $jenis->nama_jenis_prestasi ?? '-',
                'id_tingkat_prestasi' => $tingkat->id_tingkat_prestasi ?? $request->tingkat_prestasi,
                'nama_tingkat_prestasi' => $tingkat->nama_tingkat_prestasi ?? '-',
                'tahun_prestasi' => $request->tahun_prestasi,
                'penyelenggara' => $request->penyelenggara,
            ]);

            DB::commit();

            return redirect()
                ->route('mahasiswa.prestasi-skpi.index')
                ->with('success', 'Data berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat update data: ' . $e->getMessage());
        }
    }

    public function delete_prestasi_mahasiswa($id)
    {
        DB::beginTransaction();

        try {
            $prestasi = PrestasiMahasiswa::findOrFail($id);

            if ($prestasi->approved > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Data yang sudah diverifikasi tidak dapat dihapus.');
            }

            if ($prestasi->file_prestasi && Storage::disk('public')->exists($prestasi->file_prestasi)) {
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