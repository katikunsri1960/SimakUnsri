<?php

namespace App\Models\Connection;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;
    protected $connection = 'keu_con'; // Koneksi keuangan
    protected $table = 'pembayaran';
}