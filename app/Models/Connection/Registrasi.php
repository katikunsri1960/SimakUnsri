<?php

namespace App\Models\Connection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    use HasFactory;

    protected $connection = 'reg_con'; // Koneksi Laman Reg

    protected $table = 'reg_master';
}
