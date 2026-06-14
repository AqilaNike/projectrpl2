<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('petugas', function (Blueprint $table) {
            $table->string('idpetugas', 12)->primary();
            $table->string('idpengguna', 12);
            $table->string('namapetugas', 100);
            $table->string('nomorinduk', 20)->nullable();
            $table->enum('statusaktif', ['aktif', 'nonaktif'])->default('aktif');
            $table->enum('shift', ['pagi', 'siang', 'malam'])->default('pagi');
            $table->timestamps();
            $table->foreign('idpengguna')->references('idpengguna')->on('pengguna')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('petugas'); }
};