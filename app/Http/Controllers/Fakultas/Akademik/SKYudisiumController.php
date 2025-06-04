<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\FileFakultas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SKYudisiumController extends Controller
{
    public function index(Request $request)
    {
        $fakultas_id = auth()->user()->fk_id;

        $data = FileFakultas::with(['fakultas'])->where('fakultas_id', $fakultas_id)->orderBy('id', 'ASC')->get();
        // dd($data);

        return view('fakultas.data-akademik.wisuda.sk-yudisium.index', compact('data'));
    }

    // public function store(Request $request)
    // {
    //     $fakultas_id = auth()->user()->fk_id;
    //     // dd($request->all());

    //     $request->validate([
    //         'tgl_sk_yudisium' => 'required|date',
    //         'tgl_yudisium' => 'required|date',
    //         'no_sk_yudisium' => 'required|string|max:255',
    //         'sk_yudisium_file' => 'required|file|mimes:pdf|max:1024',
    //     ]);

        
    //     try {
    //         $file = $request->file('sk_yudisium_file');
    //         $skUuid = Uuid::uuid4()->toString();
    //         $folderPath = storage_path('app/public/wisuda/sk_yudisium');
    //         if (!file_exists($folderPath)) {
    //             mkdir($folderPath, 0775, true);
    //         }
    //         $skYudisiumPath = $file->storeAs('wisuda/sk_yudisium', $skUuid . '.' . $file->getClientOriginalExtension(), 'public');
    //         $sk_yudisium_file = 'storage/' . $skYudisiumPath;

            
    //         // Tambahkan create ke tabel file_fakultas
    //         FileFakultas::create([
    //             'fakultas_id' => $fakultas_id,
    //             'nama_file' => $request->no_sk_yudisium,
    //             'tgl_surat' => $request->tgl_sk_yudisium,
    //             'tgl_kegiatan' => $request->tgl_yudisium,
    //             'dir_file' => $sk_yudisium_file,
    //         ]);

    //         return redirect()->back()->with('success', 'SK Yudisium berhasil diupload.');
    //     } catch (\Throwable $e) {
    //         return redirect()->back()->with('error', 'Gagal upload SK Yudisium!');
    //     }
    // }

}
