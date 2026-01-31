<?php

namespace App\Models\Mahasiswa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerbaikanDataPokok extends Model
{
    use HasFactory;
    protected $table = 'perbaikan_data_pokok';
    protected $guarded = [];
}
