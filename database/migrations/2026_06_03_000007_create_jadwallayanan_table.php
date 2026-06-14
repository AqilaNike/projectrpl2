<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('jadwallayanan', function (Blueprint $table) {
            $table->string('idjadwal', 10)->primary();
            $table->string('idpoli', 10);
            $table->string('iddokter', 12);
            $table->date('tanggal');
            $table->integer('kuotamaksimal')->default(20);
            $table->integer('kuotaterisi')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->foreign('idpoli')->references('idpoli')->on('poli')->onDelete('cascade');
            $table->foreign('iddokter')->references('iddokter')->on('dokter')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('jadwallayanan'); }
};