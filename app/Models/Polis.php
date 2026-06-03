<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Polis extends Model
{
    protected $fillable = ['nama','kode','lokasi','icon','is_active','kuota_harian','jam_buka','jam_tutup'];

    public function doctors()      { return $this->hasMany(Doctor::class); }
    public function jadwals()      { return $this->hasMany(JadwalDokter::class); }
    public function antreans()     { return $this->hasMany(Antrean::class); }

    public function kuotaTersisaHariIni(): int
    {
        $terisi = $this->antreans()
            ->whereDate('tanggal', today())
            ->whereIn('status', ['menunggu','dipanggil'])
            ->count();
        return max(0, $this->kuota_harian - $terisi);
    }
}
