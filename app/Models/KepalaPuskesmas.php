<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class KepalaPuskesmas extends Model
{
    protected $table = 'kepalapuskesmas';
    protected $primaryKey = 'idkepala';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['idkepala', 'idpengguna', 'namakepala', 'nomorinduk', 'periodejabatan'];
    public function pengguna() { return $this->belongsTo(Pengguna::class, 'idpengguna', 'idpengguna'); }
    public function laporans() { return $this->hasMany(LaporanAntrean::class, 'idkepala', 'idkepala'); }
}
