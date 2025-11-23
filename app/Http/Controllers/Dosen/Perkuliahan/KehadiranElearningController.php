<?php

namespace App\Http\Controllers\Dosen\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dosen\BiodataDosen;
use App\Models\kehadiran_dosen;
use App\Models\kehadiran_mahasiswa;
use Carbon\Carbon;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Http\Request;



class KehadiranElearningController extends Controller
{
    //     public function kehadiran_elearning()
    //     {

    //     // Ambil NIP dosen yang sedang login berdasarkan fk_id
    //     $user = BiodataDosen::where('id_dosen', auth()->user()->fk_id)->value('nip');
    //     // Ambil hanya data kehadiran yang terkait dengan dosen ini
    //     $kehadiran = kehadiran::where('deskripsi_sesi', $user)->get();
    //     // dd($kehadiran,$user);
    //     // Gabungkan data kehadiran dengan data biodata dosen berdasarkan NIP
    //     foreach ($kehadiran as $data) {
    //         if ($data->deskripsi_sesi !== null) { // Jika deskripsi_sesi tidak null
    //             $dosen = BiodataDosen::where('nip', $data->deskripsi_sesi)->first();
    //             $data->nama_dosen = $dosen ? $dosen->nama_dosen : 'N/A';
    //         } else {
    //             $data->nama_dosen = 'Deskripsi Tidak Ditulis'; // Jika null, tampilkan default
    //         }

    //         if (!empty($data->session_date)) {
    //             $data->session_date = Carbon::createFromTimestamp($data->session_date)->format('d-m-Y');
    //         } else {
    //             $data->session_date = 'Tanggal Tidak Tersedia';
    //         }
    //     }
    //     // Kirim data ke view
    //     return view('dosen.penilaian.kehadiran-elearning.kehadiran-elearning', compact('kehadiran'));
    // }

    public function detail_mahasiswa($session_id)
    {
        // Ambil data kehadiran berdasarkan session_id
        $kehadiran = kehadiran_mahasiswa::where('session_id', $session_id)
            ->select('username', 'status_mahasiswa', 'nama_kelas',)
            ->get();

        $nama_mk = $kehadiran->isNotEmpty() ? $kehadiran->first()->nama_mk : 'Mata Kuliah Tidak Ditemukan';

        foreach ($kehadiran as $item) {
            if (!empty($item->username)) {
                $user = RiwayatPendidikan::where('nim', $item->username)->first();
                $item->nama_mahasiswa = $user ? $user->nama_mahasiswa : 'N/A';
            } else {
                $item->nama_mahasiswa = 'Deskripsi Tidak Ditulis';
            }
        }

        // Tampilkan halaman detail dengan data yang sesuai
        return view('dosen.perkuliahan.kehadiran-elearning.detail', compact('kehadiran', 'session_id'));
    }

    public function detail_kehadiran_dosen()
    {

        // Ambil NIP dosen yang sedang login berdasarkan fk_id
        $biodata = BiodataDosen::where('id_dosen', auth()->user()->fk_id)->first();
        if ($biodata) {
            $user = $biodata->nip
                ?: $biodata->nuptk;
        } else {
            $user = null;
        }

        // Ambil hanya data kehadiran yang terkait dengan dosen ini
        $kehadirandosen = kehadiran_dosen::where('deskripsi_sesi', $user)->get();
        $kehadiran = kehadiran_mahasiswa::all();
        // Gabungkan data kehadiran dengan data biodata dosen berdasarkan NIP

        //membuat filter nama kelas, jika kosong maka tidak akan di tampilkan.
        $kehadirandosen = collect($kehadirandosen)->filter(function ($item) {
            return $item->nama_kelas !== null;
        });

        foreach ($kehadirandosen as $datadosen) {
            if ($datadosen->deskripsi_sesi !== null) { // Jika deskripsi_sesi tidak null
                $dosen = BiodataDosen::where('nip', $datadosen->deskripsi_sesi)
                    ->orWhere('nuptk', $datadosen->deskripsi_sesi)
                    ->orWhere('nidn', $datadosen->deskripsi_sesi)
                    ->first();

                $datadosen->nama_dosen = $dosen ? $dosen->nama_dosen : 'N/A';
            } else {
                $datadosen->nama_dosen = 'Deskripsi Tidak Ditulis'; // Jika null, tampilkan default
            }

            if (!empty($datadosen->session_date)) {
                $datadosen->session_date = Carbon::createFromTimestamp($datadosen->session_date)->format('d-m-Y');
            } else {
                $datadosen->session_date = 'Tanggal Tidak Tersedia';
            }

            // Cek apakah ada data di tabel kehadiran yang memiliki session_id yang sama
            $kehadiranData = $kehadiran->where('session_id', $datadosen->session_id);
            // Ambil jumlah peserta
            $datadosen->jumlah_peserta = $kehadiranData->count();

            // Ambil status kehadiran (misalnya jumlah hadir)
            $datadosen->jumlah_hadir = $kehadiranData->where('status_mahasiswa', 'Present')->count();
            $datadosen->jumlah_terlambat = $kehadiranData->where('status_mahasiswa', 'Late')->count();
            $datadosen->jumlah_izin = $kehadiranData->where('status_mahasiswa', 'Excused')->count();
            $datadosen->jumlah_absen = $kehadiranData->where('status_mahasiswa', 'Absent')->count();

            $filteredData = $kehadirandosen->filter(function ($datadosen) {
                return !is_null($datadosen->nama_kelas);
            });
        }
        // Kirim data ke view
        return view('dosen.perkuliahan.kehadiran-elearning.kehadiran-elearning', compact('kehadirandosen', 'kehadiran'));
    }

    public function detail_kehadiran_dosen_Ajax()
    {

        // Ambil NIP dosen yang sedang login berdasarkan fk_id
        $biodata = BiodataDosen::where('id_dosen', auth()->user()->fk_id)->first();
        if ($biodata) {
            $user = $biodata->nip
                ?: $biodata->nuptk
                ?: $biodata->nidn;
        } else {
            $user = null;
        }
        // Ambil hanya data kehadiran yang terkait dengan dosen ini
        $kehadirandosen = kehadiran_dosen::where('deskripsi_sesi', $user)->get();
        $kehadiran = kehadiran_mahasiswa::all();

        // Filter data: hanya yang nama_kelas tidak null
        $kehadirandosen = collect($kehadirandosen)->filter(function ($item) {
            return $item->nama_kelas !== null;
        })->values();

        $result = [];
        foreach ($kehadirandosen as $datadosen) {
            // Ambil nama dosen
            if ($datadosen->deskripsi_sesi !== null) {
                $nip = $datadosen->deskripsi_sesi;

                // Cari berdasarkan NIP dulu
                $dosen = BiodataDosen::where('nip', $nip)->first();

                // Jika tidak ketemu, coba cari berdasarkan NIDN
                if (!$dosen) {
                    $dosen = BiodataDosen::where('nuptk', $nip)->first();
                }

                // Jika masih tidak ketemu, coba cari berdasarkan NIK
                if (!$dosen) {
                    $dosen = BiodataDosen::where('nidn', $nip)->first();
                }

                // Tentukan nama dosen
                $nama_dosen = $dosen ? $dosen->nama_dosen : 'N/A';
            } else {
                $nama_dosen = 'Deskripsi Tidak Ditulis';
            }


            // Format tanggal
            if (!empty($datadosen->session_date)) {
                $tanggal = Carbon::createFromTimestamp($datadosen->session_date)->format('d-m-Y');
            } else {
                $tanggal = 'Tanggal Tidak Tersedia';
            }

            // Cek data kehadiran mahasiswa untuk sesi ini
            $kehadiranData = $kehadiran->where('session_id', $datadosen->session_id);
            $jumlah_peserta = $kehadiranData->count();
            $jumlah_hadir = $kehadiranData->where('status_mahasiswa', 'Present')->count();
            $jumlah_terlambat = $kehadiranData->where('status_mahasiswa', 'Late')->count();
            $jumlah_izin = $kehadiranData->where('status_mahasiswa', 'Excused')->count();
            $jumlah_absen = $kehadiranData->where('status_mahasiswa', 'Absent')->count();

            $result[] = [
                'kode_mata_kuliah' => $datadosen->kode_mata_kuliah,
                'nama_mk'          => $datadosen->nama_mk,
                'nama_kelas'       => $datadosen->nama_kelas,
                'nama_dosen'       => $nama_dosen,
                'session_date'     => $tanggal,
                'jumlah_peserta'   => $jumlah_peserta,
                'session_id'       => $datadosen->session_id,
                'jumlah_hadir'     => $jumlah_hadir,
                'jumlah_terlambat' => $jumlah_terlambat,
                'jumlah_izin'      => $jumlah_izin,
                'jumlah_absen'     => $jumlah_absen,
            ];
        }

        return response()->json(['data' => $result]);
    }

    public function kehadiran_dosen()
    {
        // Ambil NIP dosen yang sedang login berdasarkan fk_id
        $biodata = BiodataDosen::where('id_dosen', auth()->user()->fk_id)->first();
        if ($biodata) {
            $user = $biodata->nip
                ?: $biodata->nuptk
                ?: $biodata->nidn;
        } else {
            $user = null;
        }


        // Ambil hanya data kehadiran yang terkait dengan dosen ini dan kelas tidak null
        $kehadirandosen = kehadiran_dosen::where('deskripsi_sesi', $user)
            ->whereNotNull('nama_kelas')
            ->get();

        // Kelompokkan berdasarkan kode & nama mata kuliah
        $rekap = $kehadirandosen->groupBy(function ($item) {
            return $item->kode_mata_kuliah . '|' . $item->nama_mk . '|' . $item->nama_kelas . '|' . $item->deskripsi_sesi;
        });

        $result = [];
        foreach ($rekap as $key => $group) {
            [$kode_mata_kuliah, $nama_mk, $nama_kelas, $nip_dosen] = explode('|', $key);

            // Ambil nama dosen pengajar
            $dosen = BiodataDosen::where('nip', $nip_dosen)->first()
                ?? BiodataDosen::where('nuptk', $nip_dosen)->first()
                ?? BiodataDosen::where('nidn', $nip_dosen)->first();

            $nama_dosen = $dosen ? $dosen->nama_dosen : 'N/A';

            $result[] = [
                'kode_mata_kuliah' => $kode_mata_kuliah,
                'nama_mk'          => $nama_mk,
                'nama_kelas'       => $nama_kelas,
                'total_kehadiran'  => $group->count(),
                'dosen_pengajar'   => $nama_dosen,
                'detail_pertemuan' => $group->map(function ($item, $idx) {
                    return [
                        'pertemuan_ke' => $idx + 1,
                        'tanggal'      => \Carbon\Carbon::createFromTimestamp($item->session_date)->format('d-m-Y'),
                        'status'       => $item->status, // sesuaikan dengan field pada model
                    ];
                })->values()->all(),
            ];
        }

        // Kirim hasil rekap ke view
        return view('dosen.perkuliahan.kehadiran-elearning.detail-dosen', [
            'rekap' => $result
        ]);
    }

    public function detail_pertemuan_dosen(Request $request)
    {
        $kodeMk = $request->input('kode_mk');
        $namaKelas = $request->input('nama_kelas');

        // Ambil NIP dosen yang sedang login berdasarkan fk_id
        $biodata = BiodataDosen::where('id_dosen', auth()->user()->fk_id)->first();
        if ($biodata) {
            $user = $biodata->nip
                ?: $biodata->nuptk
                ?: $biodata->nidn;
        } else {
            $user = null;
        }

        // Ambil semua data kehadiran dosen untuk mata kuliah, kelas, dan dosen ini
        $kehadirandosen = kehadiran_dosen::where('deskripsi_sesi', $user)
            ->where('kode_mata_kuliah', $kodeMk)
            ->where('nama_kelas', $namaKelas)
            ->get();

        // Kelompokkan berdasarkan session_id agar tidak ganda
        $kehadirandosen = collect($kehadirandosen)
            ->filter(fn($item) => $item->nama_kelas !== null)
            ->groupBy('session_id')
            ->map(function ($items) {
                $item = $items->first();

                // Format tanggal
                $item->tanggal = $item->session_date
                    ? Carbon::createFromTimestamp($item->session_date)->format('d-m-Y')
                    : 'Tanggal Tidak Tersedia';

                // Nama dosen
                if ($item->deskripsi_sesi !== null) {
                    $dosen = BiodataDosen::where('nip', $item->deskripsi_sesi)->first()
                        ?? BiodataDosen::where('nuptk', $item->deskripsi_sesi)->first()
                        ?? BiodataDosen::where('nidn', $item->deskripsi_sesi)->first();

                    $item->nama_dosen = $dosen ? $dosen->nama_dosen : 'N/A';
                } else {
                    $item->nama_dosen = 'Deskripsi Tidak Ditulis';
                }

                // Ambil data mahasiswa berdasarkan session_id
                $kehadiran_mahasiswa = kehadiran_mahasiswa::where('session_id', $item->session_id)->get();
                $item->jumlah_peserta = $kehadiran_mahasiswa->count();
                $item->jumlah_hadir = $kehadiran_mahasiswa->where('status_mahasiswa', 'Present')->count();
                $item->jumlah_terlambat = $kehadiran_mahasiswa->where('status_mahasiswa', 'Late')->count();
                $item->jumlah_izin = $kehadiran_mahasiswa->where('status_mahasiswa', 'Excused')->count();
                $item->jumlah_absen = $kehadiran_mahasiswa->where('status_mahasiswa', 'Absent')->count();

                return $item;
            })->values(); // reset indexing

        return view('dosen.perkuliahan.kehadiran-elearning.detail-pertemuan', [
            'kehadirandosen' => $kehadirandosen,
            'kode_mk' => $kodeMk,
            'nama_kelas' => $namaKelas,
        ]);
    }
}
