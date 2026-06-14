<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Dokter extends Model
{
    protected $table = 'dokter';
    protected $primaryKey = 'iddokter';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['iddokter', 'idpengguna', 'namadokter', 'jenisdokter'];
    public function pengguna()    { return $this->belongsTo(Pengguna::class, 'idpengguna', 'idpengguna'); }
    public function jadwals()     { return $this->hasMany(JadwalLayanan::class, 'iddokter', 'iddokter'); }
    public function rekammedis()  { return $this->hasMany(RekamMedis::class, 'iddokter', 'iddokter'); }
}
