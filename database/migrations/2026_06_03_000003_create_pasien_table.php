<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pasien', function (Blueprint $table) {
            $table->string('idpasien', 12)->primary();
            $table->string('idpengguna', 12);
            $table->char('nik', 16)->unique();
            $table->string('namapasien', 100);
            $table->string('nomorhp', 15)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->enum('jeniskelamin', ['L', 'P'])->nullable();
            $table->date('tanggallahir')->nullable();
            $table->timestamps();
            $table->foreign('idpengguna')->references('idpengguna')->on('pengguna')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('pasien'); }
};