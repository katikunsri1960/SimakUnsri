<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KuisonerAnswer;
use App\Models\KuisonerQuestion;
use Illuminate\Http\Request;

class ApiKuisioner extends Controller
{
    public function kuisoner_questions()
    {
        $kuisonerQuestions = KuisonerQuestion::query()
            ->select([
                'id',
                'question_indonesia',
                'question_english',
                'created_at',
            ])
            ->orderBy('id', 'asc')
            ->paginate(500);

        return response()->json([
            'kuisoner_questions' => $kuisonerQuestions,
        ]);
    }

    public function kuisoner_answers(Request $request)
    {
        /*
         * ID terakhir dari request sebelumnya.
         * Request pertama menggunakan after_id=0.
         */
        $afterId = max(
            (int) $request->query('after_id', 0),
            0
        );

        /*
         * Default 5.000 data dan maksimal 5.000.
         */
        $limit = (int) $request->query('limit', 5000);
        $limit = max(500, min($limit, 5000));

        /*
         * Mengambil satu baris tambahan untuk mengetahui
         * apakah masih ada data pada batch berikutnya.
         */
        $results = KuisonerAnswer::query()
            ->select([
                'id',
                'kuisoner_question_id',
                'id_kelas_kuliah',
                'id_registrasi_mahasiswa',
                'nilai',
                'created_at',
            ])
            ->where('id', '>', $afterId)
            ->orderBy('id', 'asc')
            ->limit($limit + 1)
            ->get();

        $hasMore = $results->count() > $limit;

        /*
         * Data yang dikirim tetap maksimal sesuai limit.
         */
        $data = $results
            ->take($limit)
            ->values();

        /*
         * last_id dipakai sebagai after_id pada request berikutnya.
         */
        $lastId = $data->isNotEmpty()
            ? (int) $data->last()->id
            : $afterId;

        return response()->json([
            'kuisoner_answers' => [
                'data' => $data,
                'count' => $data->count(),
                'last_id' => $lastId,
                'has_more' => $hasMore,
            ],
        ]);
    }
}
