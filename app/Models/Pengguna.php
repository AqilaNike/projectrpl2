<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Pengguna extends Authenticatable
{
    use Notifiable;
    protected $table = 'pengguna';
    protected $primaryKey = 'idpengguna';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['idpengguna', 'email', 'passwordhash', 'role', 'statusakun'];
    protected $hidden = ['passwordhash', 'remember_token'];
    public function getAuthPassword() { return $this->passwordhash; }

    public function getNameAttribute(): string
    {
        if ($this->isAdmin()) return 'Administrator';
        if ($this->isDokter() && $this->dokter) return $this->dokter->namadokter;
        if ($this->isPasien() && $this->pasien) return $this->pasien->namapasien;
        if ($this->isPetugas() && $this->petugas) return $this->petugas->namapetugas;
        if ($this->isKepala() && $this->kepala) return $this->kepala->namakepala;
        return $this->email;
    }

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isDokter(): bool  { return $this->role === 'dokter'; }
    public function isPasien(): bool  { return $this->role === 'pasien'; }
    public function isPetugas(): bool { return $this->role === 'petugas'; }
    public function isKepala(): bool  { return $this->role === 'kepala'; }
    public function pasien()    { return $this->hasOne(Pasien::class, 'idpengguna', 'idpengguna'); }
    public function dokter()    { return $this->hasOne(Dokter::class, 'idpengguna', 'idpengguna'); }
    public function petugas()   { return $this->hasOne(Petugas::class, 'idpengguna', 'idpengguna'); }
    public function kepala()    { return $this->hasOne(KepalaPuskesmas::class, 'idpengguna', 'idpengguna'); }
}
