<?php

use App\Models\WisudaSyaratAdm;
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
        Schema::create('wisuda_syarat_adms', function (Blueprint $table) {
            $table->id();
            $table->integer('urutan')->unique();
            $table->text('syarat');
            $table->timestamps();
        });

        $data = [
            [
                'urutan' => 1,
                'syarat' => 'Fotokopi Daftar Nilai Akademik (Transkrip) dari Fakultas/Program sesuai format dan blanko yang ditetapkan.',
            ],
            [
                'urutan' => 2,
                'syarat' => 'Fotokopi judul halaman pengesahan Tugas Akhir/Skripsi/Tesis/Disertasi bagi jalur Pendidikan sarjana dan Pascasarjana.',
            ],
            [
                'urutan' => 3,
                'syarat' => 'Kartu Pengenal Mahasiswa (KPM) asli.',
            ],
            [
                'urutan' => 4,
                'syarat' => 'Pasphoto Hitam Putih ukuran 3 x 4 cm, 4 lembar (laki-laki pakai jas, wanita pakai kebaya dimasukkan ke dalam plastik, tulis Nama, Nim, Fakultas, Jurusan pada bagian belakang), tidak bercadar, tidak memakai kacamata, dan tidak memakai peci.',
            ],
            [
                'urutan' => 5,
                'syarat' => 'Biodata mahasiswa untuk pembuatan "Buku Alumni" sesuai dengan format yang ditetapkan.',
            ],

        ];

        foreach ($data as $item) {
            WisudaSyaratAdm::create($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisuda_syarat_adms');
    }
};
