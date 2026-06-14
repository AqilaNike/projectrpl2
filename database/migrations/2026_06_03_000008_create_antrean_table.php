<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('antrean', function (Blueprint $table) {
            $table->string('idantrean', 10)->primary();
            $table->string('idpasien', 12);
            $table->string('idjadwal', 10);
            $table->string('idpetugas', 12)->nullable();
            $table->string('nomorantrean', 10);
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'batal'])->default('menunggu');
            $table->string('alasanbatal', 255)->nullable();
            $table->dateTime('waktudaftar')->nullable();
            $table->dateTime('waktupanggil')->nullable();
            $table->dateTime('waktuselesai')->nullable();
            $table->integer('estimasitunggu')->nullable();
            $table->enum('jenispasien', ['BPJS', 'UMUM'])->default('UMUM');
            $table->timestamps();
            $table->foreign('idpasien')->references('idpasien')->on('pasien')->onDelete('cascade');
            $table->foreign('idjadwal')->references('idjadwal')->on('jadwallayanan')->onDelete('cascade');
            $table->foreign('idpetugas')->references('idpetugas')->on('petugas')->onDelete('set null');
        });
    }
    public function down(): void { Schema::dropIfExists('antrean'); }
};