<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Libraries\nusoap;

class CreateAccountController extends Controller
{
    public function createAccountMahasiswa()
    {
        return view('auth.create-account-mahasiswa');
    }

    public function checkNim(string $nim)
    {
        $user = RiwayatPendidikan::select('nama_mahasiswa')->where('nim', $nim)->first();
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

    public function testNusoap(string $nim, string $tak_pembayaran)
    {
        $url = "http://103.241.4.52/services/host2host.php";
        $tak_pembayaran = "20241";
        require_once app_path('Libraries/nusoap.php');
        $client = new \nusoap_client($url, true);
        $param = array(
            'nim'			=>	$nim,
            'kode'			=>	$tak_pembayaran
         );
        $client->soap_defencoding = 'UTF-8';
        $response = $client->call('CheckPembayaran', $param);
        dd($response);
        return response()->json($response);
    }

    public function storeAkun(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|exists:riwayat_pendidikans,nim',
            'password' => 'required|confirmed',
        ]);

        $user = RiwayatPendidikan::where('nim', $data['nim'])->first();
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
