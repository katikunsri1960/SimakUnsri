<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SemesterAktif extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function krsMulai(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => date('d-m-Y', strtotime($value)),
            set: fn(string $value) => date('Y-m-d', strtotime($value))
        );
    }

    public function krsSelesai(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => date('d-m-Y', strtotime($value)),
            set: fn(string $value) => date('Y-m-d', strtotime($value))
        );
    }
}
