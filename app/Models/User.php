<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // tambahkan role
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cek role owner
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    // Cek role karyawan
    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }
}
