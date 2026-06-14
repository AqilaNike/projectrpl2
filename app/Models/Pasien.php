<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pasien extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'idpasien';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['idpasien', 'idpengguna', 'nik', 'namapasien', 'nomorhp', 'alamat', 'jeniskelamin', 'tanggallahir'];
    protected $casts = ['tanggallahir' => 'date'];
    public function pengguna() { return $this->belongsTo(Pengguna::class, 'idpengguna', 'idpengguna'); }
    public function antreans() { return $this->hasMany(Antrean::class, 'idpasien', 'idpasien'); }
    public function rekammedis() { return $this->hasMany(RekamMedis::class, 'idpasien', 'idpasien'); }
}
