<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Petugas extends Model
{
    protected $table = 'petugas';
    protected $primaryKey = 'idpetugas';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['idpetugas', 'idpengguna', 'namapetugas', 'nomorinduk', 'statusaktif', 'shift'];
    public function pengguna() { return $this->belongsTo(Pengguna::class, 'idpengguna', 'idpengguna'); }
    public function antreans() { return $this->hasMany(Antrean::class, 'idpetugas', 'idpetugas'); }
}
