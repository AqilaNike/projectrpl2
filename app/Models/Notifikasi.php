<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'idnotifikasi';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['idnotifikasi', 'idantrean', 'jenisnotifikasi', 'pesan', 'statuskirim', 'waktukirim', 'nomortujuan'];
    protected $casts = ['waktukirim' => 'datetime'];
    public function antrean() { return $this->belongsTo(Antrean::class, 'idantrean', 'idantrean'); }
}
