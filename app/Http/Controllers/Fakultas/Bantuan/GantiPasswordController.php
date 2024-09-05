<?php

namespace App\Http\Controllers\Fakultas\Bantuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GantiPasswordController extends Controller
{
    public function ganti_password()
    {
        return view('fakultas.bantuan.ganti-password');
    }

    public function proses_ganti_password(Request $request) {
        // dd($request->all());
		if ($request->new_password == $request->confirm_password) {
			
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            return redirect()->route('fakultas.bantuan.ganti-password')->with('success', 'Perubahan Password Berhasil !!!');

		} else {
			return redirect()->route('fakultas.bantuan.ganti-password')->with('error', 'Perubahan Password Gagal !!!');
		}
	}
}
