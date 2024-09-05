<?php

namespace App\Models\Connection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usept extends Model
{
    use HasFactory;
    protected $connection = 'usept_con'; // Koneksi USEPT
    protected $table = 'toefl_result';
}
