<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->string('idpengguna', 12)->primary();
            $table->string('email', 100)->unique();
            $table->string('passwordhash', 255);
            $table->enum('role', ['admin', 'pasien', 'dokter', 'petugas', 'kepala']);
            $table->string('statusakun', 10)->default('aktif');
            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pengguna'); }
};