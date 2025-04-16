<?php

namespace App\Models\Connection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $connection = 'keu_con'; // Koneksi keuangan

    protected $table = 'pembayaran';
}
