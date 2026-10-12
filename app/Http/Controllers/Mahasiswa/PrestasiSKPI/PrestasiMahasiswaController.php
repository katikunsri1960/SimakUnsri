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

        $data = SKPI::with(['prestasi', 'jenisSkpi', 'jenisSkpi.bidang', 'jenisSkpi.subBidang'])
            ->where('id_registrasi_mahasiswa', $id_reg_mhs)
            ->whereHas('jenisSkpi', function ($query) {
                // Filter Bidang D (ID: 4) dan Sub Bidang Kompetisi (ID: 1 - sesuaikan ID ini)
                $query->where('bidang_id', 4)
                    ->where('sub_bidang_id', 1); 
            })
            ->get();

            // dd($data); // Debugging line to inspect the data structure

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

    public function store_prestasi_mahasiswa(Request $request) 
        {$request->validate([
            'kategori_prestasi' => 'required|in:1,2',
            'nama_prestasi'     => 'required|string|max:255',
            'jenis_prestasi'    => 'required|exists:skpi_jenis_kegiatan,id',
            'tingkat_prestasi'  => 'required|exists:tingkat_prestasis,id_tingkat_prestasi',
            'tahun_prestasi'    => 'required|digits:4|numeric|min:2000|max:' . date('Y'),
            'penyelenggara'     => 'required|string|max:255',
            'file_prestasi'     => 'required|mimes:pdf|max:500',
        ], [
            'file_prestasi.max'   => 'Ukuran file sertifikat maksimal 500 KB.',
            'file_prestasi.mimes' => 'Format sertifikat harus berupa file PDF.',
        ]);

        // 1. Ambil Data Mahasiswa & Referensi SKPI
        $id_reg_mhs = auth()->user()->fk_id;
        $riwayat    = RiwayatPendidikan::where('id_registrasi_mahasiswa',$id_reg_mhs)->firstOrFail();

        // Data dari tabel skpi_jenis_kegiatan (misal: "Kompetisi Olahraga")
        $jenis_skpi = SKPIJenisKegiatan::findOrFail($request->jenis_prestasi);
        $tingkat    = TingkatPrestasi::where('id_tingkat_prestasi',$request->tingkat_prestasi)->first();

        // 2. MAPPING KE TABEL REFERENSI DIKTI (jenis_prestasis)
        // Ambil string teks dari SKPI untuk dicocokkan
        $teks_dicari =$jenis_skpi->nama_jenis; // Misal: "Kompetisi Olahraga"

        // Cari di tabel jenis_prestasis yang namanya terkandung dalam teks SKPI
        $jenis_dikti = JenisPrestasi::where(function($q) use ($teks_dicari) {$q->whereRaw('? LIKE CONCAT("%", nama_jenis_prestasi, "%")', [$teks_dicari]);
            })
            ->first();

        // Jika cocok (misal: Olahraga/Seni/Sains), pakai ID Dikti tersebut. Jika tidak, fallback ke '9' (Lain-lain)
        $id_jenis_dikti = $jenis_dikti ? $jenis_dikti->id_jenis_prestasi : 9;
        $nama_jenis_dikti = $jenis_dikti ? $jenis_dikti->nama_jenis_prestasi : 'Lain-lain';

        $id_prestasi_mahasiswa = Uuid::uuid4()->toString();

        // 3. Process Upload File
        $filePath = null;
        if ($request->hasFile('file_prestasi')) {
            $file     =$request->file('file_prestasi');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'prestasi_mahasiswa_' .$riwayat->nim. '_' .$id_prestasi_mahasiswa.'.'.$extension;
            $filePath = $file->storeAs('prestasi_mahasiswa',$fileName, 'public');
        }

        // 4. Eksekusi Transaction Simpan Dua Tabel
        try {
            DB::transaction(function () use (
                $request,$riwayat, 
                $jenis_skpi,$id_jenis_dikti, 
                $nama_jenis_dikti,$tingkat, 
                $filePath,$id_prestasi_mahasiswa
            ) {

                // A. Simpan ke tabel `prestasi_mahasiswa` (Menggunakan ID Dikti yang valid)
                PrestasiMahasiswa::create([
                    'id_prestasi' => $id_prestasi_mahasiswa,
                    'approved'              => 0,
                    'id_mahasiswa'          => $riwayat->id_mahasiswa,
                    'nama_mahasiswa'        => $riwayat->nama_mahasiswa,
                    'kategori_prestasi'     => $request->kategori_prestasi,
                    'id_jenis_prestasi'     => $id_jenis_dikti, // ID valid dari Dikti (3/2/1/9)                 
                    'nama_jenis_prestasi'   =>$nama_jenis_dikti, 
                    'id_tingkat_prestasi'   => $tingkat ? $tingkat->id_tingkat_prestasi : null,
                    'nama_tingkat_prestasi' => $tingkat ? $tingkat->nama_tingkat_prestasi : null,
                    'nama_prestasi'         => $request->nama_prestasi,
                    'tahun_prestasi'        => $request->tahun_prestasi,
                    'penyelenggara'         => $request->penyelenggara,
                    'file_prestasi'         => $filePath,
                ]);

                // B. Simpan ke tabel `skpi_data` (Menggunakan data SKPI lokal)
                SKPI::create([
                    'id_registrasi_mahasiswa' => $riwayat->id_registrasi_mahasiswa,
                    'id_prodi'                => $riwayat->id_prodi,
                    'id_semester'             => $riwayat->id_semester ?? $riwayat->id_periode_masuk,
                    'nama_kegiatan'           => $request->nama_prestasi,
                    'tahun'                   => $request->tahun_prestasi,
                    'id_jenis_skpi'           => $jenis_skpi->id,
                    'nama_jenis_skpi'         => $jenis_skpi->nama_jenis,
                    'id_prestasi'              => $id_prestasi_mahasiswa,
                    'skor'                    => $jenis_skpi->skor ?? 0,
                    'file_pendukung'          => $filePath,
                    'approved'                => 0,
                ]);
            });

            return redirect()->route('mahasiswa.prestasi-skpi.index')
                ->with('success', 'Data prestasi dan SKPI berhasil disimpan!');

        } catch (\Exception $e) {
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
        // 1. Ambil data utama dari SKPI beserta relasi prestasi
        $skpi = SKPI::with(['prestasi', 'jenisSkpi'])->findOrFail($id);

        // Pengecekan status approval dari SKPI / Prestasi
        if ($skpi->approved > 0 || ($skpi->prestasi && $skpi->prestasi->approved > 0)) {
            return redirect()->route('mahasiswa.prestasi-skpi.index')
                ->with('error', 'Data yang sudah diverifikasi tidak dapat diedit.');
        }

        // 2. Mengambil referensi jenis prestasi SKPI
        $jenis_prestasi = SKPIJenisKegiatan::where('sub_bidang_id', 1)
            ->orderBy('id', 'ASC')
            ->pluck('nama_jenis', 'id')
            ->unique();

        $tingkat_prestasi = TingkatPrestasi::orderBy('id_tingkat_prestasi')->get();
        $bidang           = SKPIBidangKegiatan::all();
        $sub_bidang       = SKPISubBidangKegiatan::all();

        return view('mahasiswa.prestasi-skpi.edit', compact(
            'skpi',
            'jenis_prestasi',
            'tingkat_prestasi',
            'bidang',
            'sub_bidang'
        ));
    }

    public function update(Request $request, $id)
    {
        // 1. Ambil data SKPI utama
        $skpi = SKPI::with('prestasi')->findOrFail($id);

        if ($skpi->approved > 0 || ($skpi->prestasi && $skpi->prestasi->approved > 0)) {
            return redirect()->back()
                ->with('error', 'Data yang sudah diverifikasi tidak dapat diedit.');
        }

        $request->validate([
            'kategori_prestasi' => 'required|in:1,2',
            'nama_prestasi'     => 'required|string|max:255',
            'jenis_prestasi'    => 'required|exists:skpi_jenis_kegiatan,id',
            'tingkat_prestasi'  => 'required|exists:tingkat_prestasis,id_tingkat_prestasi',
            'tahun_prestasi'    => 'required|digits:4|numeric|min:2000|max:' . date('Y'),
            'penyelenggara'     => 'required|string|max:255',
            'file_prestasi'     => 'nullable|mimes:pdf|max:500',
        ], [
            'file_prestasi.max'   => 'Ukuran file sertifikat maksimal 500 KB.',
            'file_prestasi.mimes' => 'Format sertifikat harus berupa file PDF.',
        ]);

        DB::beginTransaction();

        try {
            $jenis_skpi = SKPIJenisKegiatan::findOrFail($request->jenis_prestasi);
            $tingkat    = TingkatPrestasi::where('id_tingkat_prestasi', $request->tingkat_prestasi)->first();

            // Matching ke tabel Dikti jenis_prestasis
            $teks_dicari  = $jenis_skpi->nama_jenis;
            $jenis_dikti  = JenisPrestasi::where(function($q) use ($teks_dicari) {
                    $q->whereRaw('? LIKE CONCAT("%", nama_jenis_prestasi, "%")', [$teks_dicari]);
                })
                ->first();

            $id_jenis_dikti   = $jenis_dikti ? $jenis_dikti->id_jenis_prestasi : 9;
            $nama_jenis_dikti = $jenis_dikti ? $jenis_dikti->nama_jenis_prestasi : 'Lain-lain';

            // Process File Upload
            $filePath = $skpi->file_pendukung;
            if ($request->hasFile('file_prestasi')) {
                $id_reg_mhs = auth()->user()->fk_id;
                $riwayat    = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();
                $nim        = $riwayat ? $riwayat->nim : 'unknown';

                // Hapus file lama jika ada
                if ($skpi->file_pendukung && Storage::disk('public')->exists($skpi->file_pendukung)) {
                    Storage::disk('public')->delete($skpi->file_pendukung);
                }
                $file     = $request->file('file_prestasi');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'prestasi_mahasiswa_' .$riwayat->nim. '_' .$skpi->id_prestasi.'.'.$extension;
                $filePath = $file->storeAs('prestasi_mahasiswa',$fileName, 'public');
            }

            // A. Update Data Utama SKPI
            $skpi->update([
                'nama_kegiatan'   => $request->nama_prestasi,
                'tahun'           => $request->tahun_prestasi,
                'id_jenis_skpi'   => $jenis_skpi->id,
                'nama_jenis_skpi' => $jenis_skpi->nama_jenis,
                'skor'            => $jenis_skpi->skor ?? 0,
                'file_pendukung'  => $filePath,
            ]);

            // B. Update Data PrestasiMahasiswa jika relasinya ada
            if ($skpi->prestasi) {
                $skpi->prestasi->update([
                    'kategori_prestasi'     => $request->kategori_prestasi,
                    'nama_prestasi'         => $request->nama_prestasi,
                    'id_jenis_prestasi'     => $id_jenis_dikti,
                    'nama_jenis_prestasi'   => $nama_jenis_dikti,
                    'id_tingkat_prestasi'   => $tingkat ? $tingkat->id_tingkat_prestasi : null,
                    'nama_tingkat_prestasi' => $tingkat ? $tingkat->nama_tingkat_prestasi : null,
                    'tahun_prestasi'        => $request->tahun_prestasi,
                    'penyelenggara'         => $request->penyelenggara,
                    'file_prestasi'         => $filePath,
                ]);
            }

            DB::commit();

            return redirect()->route('mahasiswa.prestasi-skpi.index')
                ->with('success', 'Data prestasi dan SKPI berhasil diperbarui.');

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
            // 1. Ambil data SKPI beserta relasi prestasinya
            $skpi = SKPI::with('prestasi')->findOrFail($id);

            // 2. Proteksi: Cegah hapus jika data sudah diverifikasi/disetujui
            if ($skpi->approved > 0 || ($skpi->prestasi && $skpi->prestasi->approved > 0)) {
                return redirect()->back()
                    ->with('error', 'Data yang sudah diverifikasi tidak dapat dihapus.');
            }

            // 3. Hapus File Fisik PDF dari Storage jika ada
            if ($skpi->file_pendukung && Storage::disk('public')->exists($skpi->file_pendukung)) {
                Storage::disk('public')->delete($skpi->file_pendukung);
            }

            // Juga cek file_prestasi pada tabel prestasi_mahasiswas jika jalurnya berbeda
            if ($skpi->prestasi && $skpi->prestasi->file_prestasi && Storage::disk('public')->exists($skpi->prestasi->file_prestasi)) {
                Storage::disk('public')->delete($skpi->prestasi->file_prestasi);
            }

            // 4. Hapus Record prestasi_mahasiswas (jika ada relasinya)
            if ($skpi->prestasi) {
                $skpi->prestasi->delete();
            }

            // 5. Hapus Record Utama skpi_data
            $skpi->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Data prestasi dan SKPI berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}