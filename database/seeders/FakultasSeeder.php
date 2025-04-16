<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use Illuminate\Database\Seeder;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'nama_fakultas' => 'Fakultas Ekonomi'],
            ['id' => 2, 'nama_fakultas' => 'Fakultas Hukum'],
            ['id' => 3, 'nama_fakultas' => 'Fakultas Teknik'],
            ['id' => 4, 'nama_fakultas' => 'Fakultas Kedokteran'],
            ['id' => 5, 'nama_fakultas' => 'Fakultas Pertanian'],
            ['id' => 6, 'nama_fakultas' => 'Fakultas Keguruan Dan Ilmu Pendidikan'],
            ['id' => 7, 'nama_fakultas' => 'Fakultas Ilmu Sosial Dan Ilmu Politik'],
            ['id' => 8, 'nama_fakultas' => 'Fakultas Matematika Dan Ilmu Pengetahuan Alam'],
            ['id' => 9, 'nama_fakultas' => 'Fakultas Ilmu Komputer'],
            ['id' => 10, 'nama_fakultas' => 'Fakultas Kesehatan Masyarakat'],
            ['id' => 11, 'nama_fakultas' => 'Program Pascasarjana'],
        ];

        Fakultas::insert($data);
    }
}
