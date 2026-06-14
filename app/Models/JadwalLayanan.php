<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JadwalLayanan extends Model
{
    protected $table = 'jadwallayanan';
    protected $primaryKey = 'idjadwal';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['idjadwal', 'idpoli', 'iddokter', 'tanggal', 'kuotamaksimal', 'kuotaterisi', 'status'];
    protected $casts = ['tanggal' => 'date'];
    public function poli()     { return $this->belongsTo(Poli::class, 'idpoli', 'idpoli'); }
    public function dokter()   { return $this->belongsTo(Dokter::class, 'iddokter', 'iddokter'); }
    public function antreans() { return $this->hasMany(Antrean::class, 'idjadwal', 'idjadwal'); }
    public function sisaKuota(): int { return max(0, $this->kuotamaksimal - $this->kuotaterisi); }
    public function isFull(): bool   { return $this->kuotaterisi >= $this->kuotamaksimal; }
}
