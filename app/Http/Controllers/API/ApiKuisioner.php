<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KuisonerQuestion;
use App\Models\KuisonerAnswer;
class ApiKuisioner extends Controller
{

    public function kuisoner_questions()
    {
        $kuisoner_questions = KuisonerQuestion::select(
            'id',
            'question_indonesia',
            'question_english',
            'created_at'
        )->paginate(500);

        return response()->json([
            'kuisoner_questions' => $kuisoner_questions
        ]);
    }


    public function kuisoner_answers()
    {
        $kuisoner_answers = KuisonerAnswer::select(
            'id',
            'kuisoner_question_id',
            'id_kelas_kuliah',
            'id_registrasi_mahasiswa',
            'nilai',
            'created_at'
        )->paginate(500);

        return response()->json([
            'kuisoner_answers' => $kuisoner_answers
        ]);
    }
}