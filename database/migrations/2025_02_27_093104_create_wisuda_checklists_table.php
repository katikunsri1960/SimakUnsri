<?php

use App\Models\WisudaChecklist;
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
        Schema::create('wisuda_checklists', function (Blueprint $table) {
            $table->id();
            $table->integer('urutan')->unique();
            $table->text('checklist');
            $table->timestamps();
        });

        $data = [
            [
                'urutan' => 1,
                'checklist' => 'Telah lulus dan terdaftar dalarn usulan calon wisudawan/wisudawati yang diajukan dengan surat tertulis oleh Dekan/Direktur Program.',
            ],
            [
                'urutan' => 2,
                'checklist' => 'Mengisi dan menyerahkan blanko formulir pendaftaran wisuda (blanko diperbanyak di Fakultas/Program).',
            ],
            [
                'urutan' => 3,
                'checklist' => 'Menyerahkan screenshot/printscreen status kelulusan di PDDIKTI.',
            ],
            [
                'urutan' => 4,
                'checklist' => 'Menyerahkan 1 (satu) lembar SK Yudisium.',
            ],
            [
                'urutan' => 5,
                'checklist' => 'Menyerahkan 1 (satu) lembar fotokopi pengesahan tugas akhir.',
            ],
            [
                'urutan' => 6,
                'checklist' => 'Menyerahkan bukti abstrak paper yang telah ditandatangani oleh pembimbing dan ketua program studi.',
            ],
            [
                'urutan' => 7,
                'checklist' => 'Menyerahkan 4 (empat) lembar pas foto terbaru hitam putih dengan latar putih ukuran 3x4cm (laki-laki memakai jas dan berdasi, wanita memakai kebaya dan selendang songket).',
            ],
            [
                'urutan' => 8,
                'checklist' => 'Menyerahkan/mengembalikan Kartu Pengenal Mahasiswa (KPM) Asli.',
            ],
            [
                'urutan' => 9,
                'checklist' => 'Menyerahkan bukti lulus Sriwijaya University Language Institute English Test (SULIET) Universitas Sriwijaya English Proficiency Test (USEPT) 1 (satu) lembar fotokopi yang telah dilegalisir.',
            ],
            [
                'urutan' => 10,
                'checklist' => 'Menyerahkan bukti bayar UKT terakhir.',
            ],
            [
                'urutan' => 11,
                'checklist' => 'Menyerahkan kartu bebas pustaka dari UPT. Perpustakaan Universitas Sriwijaya.',
            ],
            [
                'urutan' => 12,
                'checklist' => 'Menyerahkan bukti Tugas Akhir, Skripsi, Disertasi ke repository Universitas Sriwijaya.',
            ],
            [
                'urutan' => 13,
                'checklist' => 'Menyerahkan 1 (satu) lembar fotokopi ijazah terakhir.',
            ],
            [
                'urutan' => 14,
                'checklist' => 'Semua berkas tersebut dimasukan ke dalam map plastik transparan dan diserahkan pada waktu pendaftaran.',
            ],
            [
                'urutan' => 15,
                'checklist' => 'Calon wisudawan hanya diperkenankan membawa 2 orang pendamping.',
            ],
        ];

        foreach ($data as $item) {
            WisudaChecklist::create($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisuda_checklists');
    }
};
