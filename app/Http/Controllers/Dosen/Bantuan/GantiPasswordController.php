<?php

namespace App\Http\Controllers\Dosen\Bantuan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GantiPasswordController extends Controller
{
    public function ganti_password()
    {
        return view('dosen.bantuan.ganti-password');
    }

    public function proses_ganti_password(Request $request)
    {
        // dd($request->all());
        if ($request->new_password == $request->confirm_password) {

            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->route('dosen.bantuan.ganti-password')->with('success', 'Perubahan Password Berhasil !!!');

        } else {
            return redirect()->route('dosen.bantuan.ganti-password')->with('error', 'Perubahan Password Gagal !!!');
        }
    }
}
