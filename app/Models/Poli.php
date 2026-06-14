<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Poli extends Model
{
    protected $table = 'poli';
    protected $primaryKey = 'idpoli';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['idpoli', 'namapoli', 'kuotaharian', 'statusbuka', 'jamoperasional'];
    public function jadwals()  { return $this->hasMany(JadwalLayanan::class, 'idpoli', 'idpoli'); }
    public function laporans() { return $this->hasMany(LaporanAntrean::class, 'idpoli', 'idpoli'); }
    public function kuotaTersisaHariIni(): int
    {
        $terisi = Antrean::whereHas('jadwal', function ($q) {
            $q->where('idpoli', $this->idpoli);
        })->whereHas('jadwal', function ($q) {
            $q->where('tanggal', today());
        })->whereIn('status', ['menunggu', 'dipanggil'])->count();
        return max(0, $this->kuotaharian - $terisi);
    }
}
