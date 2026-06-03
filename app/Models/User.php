<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'nik', 'no_bpjs', 'email', 'no_hp',
        'password', 'role', 'tanggal_lahir', 'jenis_kelamin', 'alamat',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isDoctor(): bool   { return $this->role === 'doctor'; }
    public function isPatient(): bool  { return $this->role === 'patient'; }

    public function antreans()         { return $this->hasMany(Antrean::class); }
    public function doctor()           { return $this->hasOne(Doctor::class); }
    public function notifikasis()      { return $this->hasMany(Notifikasi::class); }
}
