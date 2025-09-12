<?php

namespace App\Http\Controllers\DPPM;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class GantiPasswordController extends Controller
{
    public function ganti_password()
    {
        return view('dppm.bantuan.ganti-password');
    }

    public function proses_ganti_password(Request $request) {
        // dd($request->all());
		if ($request->new_password == $request->confirm_password) {
			
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            return redirect()->route('dppm.bantuan.ganti-password')->with('success', 'Perubahan Password Berhasil !!!');

		} else {
			return redirect()->route('dppm.bantuan.ganti-password')->with('error', 'Perubahan Password Gagal !!!');
		}
	}
}
