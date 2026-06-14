<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LaporanAntrean extends Model
{
    protected $table = 'laporanantrean';
    protected $primaryKey = 'idlaporan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['idlaporan', 'idkepala', 'idpoli', 'tgllaporan', 'totalpasien', 'totalselesai', 'totalbatal', 'ratawaktutunggu', 'totaltidakhadir'];
    protected $casts = ['tgllaporan' => 'date'];
    public function kepala() { return $this->belongsTo(KepalaPuskesmas::class, 'idkepala', 'idkepala'); }
    public function poli()   { return $this->belongsTo(Poli::class, 'idpoli', 'idpoli'); }
}
