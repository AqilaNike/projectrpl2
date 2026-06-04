<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Antrean extends Model
{
    protected $fillable = [
        'user_id','poli_id','doctor_id','jadwal_id',
        'nomor_antrean','tanggal','jam_kedatangan','estimasi_layanan',
        'status','keluhan','qr_code','dipanggil_at','selesai_at',
    ];
    protected $casts = ['tanggal' => 'date', 'dipanggil_at' => 'datetime', 'selesai_at' => 'datetime'];

    public function user()   { return $this->belongsTo(User::class); }
    public function poli()   { return $this->belongsTo(Polis::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function jadwal() { return $this->belongsTo(JadwalDokter::class, 'jadwal_id'); }

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

    public static function generateNomor(string $kode, string $tanggal): string
    {
        $lastToday = self::where('poli_id', function($q) use ($kode) {
                $q->select('id')->from('polis')->where('kode', $kode)->limit(1);
            })
            ->whereDate('tanggal', $tanggal)
            ->count();
        return $kode . '-' . str_pad($lastToday + 1, 3, '0', STR_PAD_LEFT);
    }
}
