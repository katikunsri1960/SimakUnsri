<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_elearning extends Model
{
    use HasFactory;
     protected $table = 'user_elearning';
    protected $fillable = [
         'id_user',
         'username',
    ];
}
