<?php

namespace App\Models\Connection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseUsept extends Model
{
    use HasFactory;

    protected $connection = 'usept_con'; // Koneksi USEPT

    protected $table = 'course_result';

    protected $appends = 'konversi';

    public function getKonversiAttribute()
    {
        // $nilai_angka = $this->total_score;
        // $nilai_huruf = $this->grade;
        // // dd($nilai_angka);
        // if($nilai_huruf == 'A'){
        //     $nilai_hasil_course = ((525-476)/(100-86)) * ($nilai_angka - 86) + 476;
        // }
        // else if($nilai_huruf == 'B'){
        //     $nilai_hasil_course = ((475-375)/(85.99-71)) * ($nilai_angka - 71) + 375;
        // }
        // else{
        //     $nilai_hasil_course = 310;
        // }

        // return round($nilai_hasil_course,0);

        $nilai_angka = $this->total_score;
        $nilai_huruf = $this->grade;

        $nilai_angka = str_replace(',', '.', $nilai_angka);

        // dd($nilai_angka);
        if ($nilai_huruf == 'A') {
            $nilai_hasil_course = ((525 - 500) / (100 - 86)) * ($nilai_angka - 86) + 500;
        } elseif ($nilai_huruf == 'B') {
            $nilai_hasil_course = ((499 - 450) / (85.99 - 71)) * ($nilai_angka - 71) + 450;
        } else {
            $nilai_hasil_course = 300;
        }

        return round($nilai_hasil_course, 0);
    }

    public function KonversiNilaiUsept($nilai_huruf, $nilai_angka)
    {
        // dd($nilai_angka);
        // if($nilai_huruf == 'A'){
        //     $nilai_hasil_course = ((525-476)/(100-86)) * ($nilai_angka - 86) + 476;
        // }
        // else if($nilai_huruf == 'B'){
        //     $nilai_hasil_course = ((475-375)/(85.99-71)) * ($nilai_angka - 71) + 375;
        // }
        // else{
        //     $nilai_hasil_course = 0;
        // }

        // return round($nilai_hasil_course,0);

        // dd($nilai_angka);

        $nilai_angka = str_replace(',', '.', $nilai_angka);

        if ($nilai_huruf == 'A') {
            $nilai_hasil_course = ((525 - 500) / (100 - 86)) * ($nilai_angka - 86) + 500;
        } elseif ($nilai_huruf == 'B') {
            $nilai_hasil_course = ((499 - 450) / (85.99 - 71)) * ($nilai_angka - 71) + 450;
        } else {
            $nilai_hasil_course = 300;
        }

        return round($nilai_hasil_course, 0);
    }
}
