<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\KuisonerQuestion;
use Illuminate\Http\Request;

class KuisionerController extends Controller
{
    public function index()
    {
        $data = KuisonerQuestion::all();

        return view('universitas.kuisioner.index', [
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question_indonesia' => 'required',
            'question_english' => 'required',
        ]);

        KuisonerQuestion::create($data);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, KuisonerQuestion $kuisioner)
    {
        $data = $request->validate([
            'question_indonesia' => 'required',
            'question_english' => 'required',
        ]);

        $kuisioner->update($data);

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(KuisonerQuestion $kuisioner)
    {
        $kuisioner->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
