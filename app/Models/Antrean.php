<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Antrean extends Model
{
    protected $table = 'antrean';
    protected $primaryKey = 'idantrean';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'idantrean', 'idpasien', 'idjadwal', 'idpetugas',
        'nomorantrean', 'status', 'alasanbatal',
        'waktudaftar', 'waktupanggil', 'waktuselesai',
        'estimasitunggu', 'jenispasien',
    ];
    protected $casts = [
        'waktudaftar'   => 'datetime',
        'waktupanggil'  => 'datetime',
        'waktuselesai'  => 'datetime',
    ];
    public function pasien()  { return $this->belongsTo(Pasien::class, 'idpasien', 'idpasien'); }
    public function jadwal()  { return $this->belongsTo(JadwalLayanan::class, 'idjadwal', 'idjadwal'); }
    public function petugas() { return $this->belongsTo(Petugas::class, 'idpetugas', 'idpetugas'); }
    public function notifikasis() { return $this->hasMany(Notifikasi::class, 'idantrean', 'idantrean'); }
    public function rekammedis()  { return $this->hasOne(RekamMedis::class, 'idantrean', 'idantrean'); }
    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'menunggu'  => 'bg-tertiary-fixed text-on-tertiary-fixed-variant',
            'dipanggil' => 'bg-primary text-on-primary animate-pulse',
            'selesai'   => 'bg-secondary-container text-on-secondary-container',
            'batal'     => 'bg-error-container text-on-error-container',
            default     => 'bg-outline-variant text-on-surface',
        };
    }
    public static function generateNomor(string $idpoli, string $tanggal): string
    {
        $poli = Poli::find($idpoli);
        $kode = strtoupper(substr($poli->namapoli, -1));
        $count = self::whereHas('jadwal', function ($q) use ($idpoli, $tanggal) {
            $q->where('idpoli', $idpoli)->where('tanggal', $tanggal);
        })->count();
        return $kode . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }
}
