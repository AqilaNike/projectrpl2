<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->string('idnotifikasi', 10)->primary();
            $table->string('idantrean', 10);
            $table->enum('jenisnotifikasi', ['pendaftaran', 'panggilan', 'pembatalan', 'selesai'])->default('pendaftaran');
            $table->text('pesan');
            $table->enum('statuskirim', ['pending', 'terkirim', 'gagal'])->default('pending');
            $table->dateTime('waktukirim')->nullable();
            $table->string('nomortujuan', 15)->nullable();
            $table->timestamps();
            $table->foreign('idantrean')->references('idantrean')->on('antrean')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('notifikasi'); }
};