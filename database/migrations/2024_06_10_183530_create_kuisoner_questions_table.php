<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kuisoner_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_indonesia');
            $table->text('question_english');
            $table->timestamps();
        });

        $data = [
            [
                'question_indonesia' => 'Materi kuliah yang disampaikan sesuai dengan Rencana Pembelajaran Semester (RPS)',
                'question_english' => 'The lecture material delivered is in accordance with the Semester Learning Plan (SLP)',
            ],
            ['question_indonesia' => 'Dosen menguasai materi yang disampaikan', 'question_english' => 'Lecturer master the subject’s matter'],
            ['question_indonesia' => 'Cara penyampaian materi perkuliahan oleh dosen mudah dipahami', 'question_english' => 'Delivery of lecture material is easy to understand'],
            ['question_indonesia' => 'Interaksi antara dosen dan mahasiswa selama perkuliahan baik', 'question_english' => 'The interaction between lecturer and students during lectures is good'],
            ['question_indonesia' => 'Evaluasi yang diberikan sesuai dengan materi pembelajaran  ', 'question_english' => 'The evaluasion given is in accordance with the learning material'],
            ['question_indonesia' => 'Dosen memberikan umpan balik berupa pembahasan atau jawaban terhadap evaluasi pembelajaran', 'question_english' => 'Lecturers give feedback in the form of explanations of the evaluation'],
            ['question_indonesia' => 'Pemberian kesempatan memperbaiki nilai dengan ujian remedi ', 'question_english' => 'Providing opportunities to improve grades with remedial exams'],
            ['question_indonesia' => 'Beban belajar yang diberikan oleh dosen sesuai dengan bobot SKS mata kuliah', 'question_english' => 'The work load given by the lecturer is in accordance with the weight of course credits'],
        ];

        foreach ($data as $key => $value) {
            \App\Models\KuisonerQuestion::create($value);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuisoner_questions');
    }
};
