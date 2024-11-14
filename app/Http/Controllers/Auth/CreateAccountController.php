<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Libraries\nusoap;
use App\Models\Mahasiswa\LulusDo;

class CreateAccountController extends Controller
{
    public function createAccountMahasiswa()
    {
        return view('auth.create-account-mahasiswa');
    }

    public function checkNim(string $nim)
    {
        $user = RiwayatPendidikan::select('nama_mahasiswa')->where('nim', $nim)->orderBy('id_jenis_daftar', 'desc')->first();

        if ($user) {
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'NIM tidak ditemukan'
            ]);
        }

    }

    public function storeAkun(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|exists:riwayat_pendidikans,nim',
            'password' => 'required|confirmed',
        ]);

        $check = LulusDo::where('nim', $data['nim'])->first();

        if ($check && $check->id_jenis_keluar == 1) {
            return redirect()->back()->with('error', 'NIM ini Sudah dinyatakan '. $check->nama_jenis_keluar);
        }

        $user = RiwayatPendidikan::where('nim', $data['nim'])->orderBy('id_periode_masuk', 'desc')->first();
        $data['username'] = $user->nim;
        $data['fk_id'] = $user->id_registrasi_mahasiswa;
        $data['name'] = $user->nama_mahasiswa;
        $data['email'] = $user->nim."@student.unsri.ac.id";
        $data['role'] = 'mahasiswa';
        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->route('login')->with('success', 'Silahkan Melakukan Login');
    }
}
