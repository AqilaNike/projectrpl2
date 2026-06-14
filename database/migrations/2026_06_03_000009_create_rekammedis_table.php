<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('rekammedis', function (Blueprint $table) {
            $table->string('idrekammedis', 12)->primary();
            $table->string('idpasien', 12);
            $table->string('iddokter', 12);
            $table->string('idantrean', 10);
            $table->string('keluhan', 255)->nullable();
            $table->string('diagnosis', 255)->nullable();
            $table->text('resep')->nullable();
            $table->dateTime('tanggalperiksa')->nullable();
            $table->timestamps();
            $table->foreign('idpasien')->references('idpasien')->on('pasien')->onDelete('cascade');
            $table->foreign('iddokter')->references('iddokter')->on('dokter')->onDelete('cascade');
            $table->foreign('idantrean')->references('idantrean')->on('antrean')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('rekammedis'); }
};