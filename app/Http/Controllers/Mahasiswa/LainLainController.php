<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SemesterAktif;
use App\Models\Mahasiswa\RiwayatPendidikan;

class LainLainController extends Controller
{
    /**
     * Tampilkan halaman portal e-voting pemilihan Presiden Mahasiswa.
     */
    public function index()
    {
        $id_reg_mhs = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::first()->id_semester;

        // Ambil riwayat pendidikan mahasiswa yang sedang login
        $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa',$id_reg_mhs)->first();

        // 1. Ambil data kandidat paslon dikelompokkan per no_urut
        $kandidats = DB::table('presma_kandidats')
                    ->orderBy('no_urut', 'ASC')
                    ->get()
                    ->groupBy('no_urut');

        $dataPaslon = [];
        foreach ($kandidats as $noUrut =>$paslon) {
            $capresma =$paslon->firstWhere('posisi', 'capresma');
            $cawapresma =$paslon->firstWhere('posisi', 'cawapresma');

            $dataDukung = DB::table('presma_data_dukungs')
                ->where('no_urut', $noUrut)
                ->pluck('deskripsi', 'kategori');

            $dataPaslon[] = (object) [
                'no_urut'    => $noUrut,
                'capresma'   => $capresma,
                'cawapresma' => $cawapresma,
                'visi'       => $dataDukung['visi'] ?? '-',
                'misi'       => $dataDukung['misi'] ?? '-',
                'foto'       => $dataDukung['foto'] ?? null,
                'video'      => $dataDukung['video'] ?? null,
                'berkas'     => $dataDukung['berkas'] ?? null,
            ];
        }

        // 2. Cek apakah mahasiswa ini sudah memberikan suara di semester AKTIF
        $alreadyVoted = null;
        if ($riwayat) {$alreadyVoted = DB::table('presma_votes')
                ->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->where('id_semester', $semester_aktif)
                ->first();
        }

        return view('mahasiswa.lain-lain.pemilihan-presma.index', [
            'kandidat'        => collect($dataPaslon),
            'alreadyVoted'    => $alreadyVoted,
            'idSemesterAktif' => $semester_aktif,
        ]);
    }

    /**
     * Proses pengiriman suara (Vote) mahasiswa.
     */
    public function vote(Request $request)
    {
        $request->validate([
            'no_urut' => 'required|numeric|exists:presma_kandidats,no_urut',
        ]);

        $id_reg_mhs = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::first()->id_semester;

        // Ambil riwayat pendidikan mahasiswa yang sedang login
        $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa',$id_reg_mhs)->first();

        if (!$riwayat) {
            return redirect()
                ->back()
                ->with('error', 'Data riwayat pendidikan mahasiswa Anda tidak ditemukan!');
        }

        // Proteksi server-side: Cek jika mahasiswa sudah vote pada semester aktif
        $existingVote = DB::table('presma_votes')
            ->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
            ->where('id_semester', $semester_aktif)
            ->exists();

        if ($existingVote) {
            return redirect()
                ->back()
                ->with('error', 'Anda sudah menggunakan hak suara Anda pada semester ini!');
        }

        DB::beginTransaction();
        try {
            DB::table('presma_votes')->insert([
                'id_semester'             => $semester_aktif,
                'id_registrasi_mahasiswa' => $riwayat->id_registrasi_mahasiswa,
                'no_urut'                 => $request->no_urut,
                'created_at'              => now(),
                'updated_at'              => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('mahasiswa.lain-lain.presiden-mahasiswa.index')
                ->with('success', 'Suara Anda berhasil disimpan! Terima kasih telah berpartisipasi.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal memproses suara Anda: ' . $e->getMessage());
        }
    }
}