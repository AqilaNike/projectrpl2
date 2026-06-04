<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    // Migration created table 'notifikases' (note the spelling).
    // Ensure model points to the correct table to avoid "table not found" errors.
    protected $table = 'notifikases';
    protected $fillable = ['user_id','judul','pesan','icon','is_read'];
    public function user() { return $this->belongsTo(User::class); }
}
