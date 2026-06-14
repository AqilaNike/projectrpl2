<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RekamMedis extends Model
{
    protected $table = 'rekammedis';
    protected $primaryKey = 'idrekammedis';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['idrekammedis', 'idpasien', 'iddokter', 'idantrean', 'keluhan', 'diagnosis', 'resep', 'tanggalperiksa'];
    protected $casts = ['tanggalperiksa' => 'datetime'];
    public function pasien()  { return $this->belongsTo(Pasien::class, 'idpasien', 'idpasien'); }
    public function dokter()  { return $this->belongsTo(Dokter::class, 'iddokter', 'iddokter'); }
    public function antrean() { return $this->belongsTo(Antrean::class, 'idantrean', 'idantrean'); }
}
