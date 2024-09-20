<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ADMINISTRATOR = 'admin';
    public const ADMIN_UNIVERSITAS = 'univ';
    public const ADMIN_FAKULTAS = 'fakultas';
    public const ADMIN_PRODI = 'prodi';
    public const DOSEN = 'dosen';
    public const MAHASISWA = 'mahasiswa';
    public const BAAK = 'bak';
    public const PERPUS = 'perpus';

    const ROLE_PRODI = 'prodi';
    const ROLE_FAKULTAS = 'fakultas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'fk_id',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function fk()
    {
        switch ($this->role) {
            case User::ROLE_PRODI:
                return $this->belongsTo(ProgramStudi::class, 'fk_id', 'id_prodi');
            case User::ROLE_FAKULTAS:
                return $this->belongsTo(Fakultas::class, 'fk_id', 'id');
            default:
                return null;
        }
    }


}
