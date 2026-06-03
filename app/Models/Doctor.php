<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['user_id','poli_id','spesialisasi','foto','rating','total_pasien','is_active'];

    public function user()     { return $this->belongsTo(User::class); }
    public function poli()     { return $this->belongsTo(Polis::class); }
    public function jadwals()  { return $this->hasMany(JadwalDokter::class); }
    public function antreans() { return $this->hasMany(Antrean::class); }

    public function getNameAttribute(): string { return $this->user->name ?? ''; }
}
