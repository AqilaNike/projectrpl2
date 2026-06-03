<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    protected $table = 'jadwal_dokters';
    protected $fillable = ['doctor_id','poli_id','tanggal','jam_mulai','jam_selesai','kuota','terisi','is_active'];
    protected $casts = ['tanggal' => 'date'];

    public function doctor()  { return $this->belongsTo(Doctor::class); }
    public function poli()    { return $this->belongsTo(Polis::class); }
    public function antreans(){ return $this->hasMany(Antrean::class, 'jadwal_id'); }

    public function sisaKuota(): int { return max(0, $this->kuota - $this->terisi); }
    public function isFull(): bool   { return $this->terisi >= $this->kuota; }
}
