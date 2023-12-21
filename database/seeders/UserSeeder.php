<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'username' => '09011181320002',
                'password' => bcrypt('09011181320002'),
                'role' => 'mahasiswa',
                'email' => '09011181320002@student.unsri.ac.id',
                'name' => 'Rian Fitra Perdana',
            ],
            [
                'username' => 'admin-univ',
                'password' => bcrypt('admin123'),
                'role' => 'univ',
                'email' => 'admin@unsri.ac.id',
                'name' => 'Admin Universitas',
            ],
            [
                'username' => 'dosen1',
                'password' => bcrypt('dosen123'),
                'role' => 'dosen',
                'email' => 'dosen@unsri.ac.id',
                'name' => 'Dosen',
            ],
            [
                'username' => 's1mesin',
                'password' => bcrypt('s1mesin'),
                'role' => 'prodi',
                'email' => 's1mesin@unsri.ac.id',
                'name' => 'Prodi S1 Mesin',
                'fk_id' => '371d293-c602-4b1b-afc5-222081477091'
            ],
        ];

        User::insert($data);

    }
}
