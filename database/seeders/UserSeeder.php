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
            'username' => '09011181320002',
            'password' => bcrypt('Eleunsri*#*#'),
            'role' => 'mahasiswa',
            'email' => '09011181320002@student.unsri.ac.id',
            'name' => 'Rian Fitra Perdana',
        ];

        User::create($data);

    }
}
