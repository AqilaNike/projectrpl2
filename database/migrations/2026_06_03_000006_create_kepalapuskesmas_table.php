<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('kepalapuskesmas', function (Blueprint $table) {
            $table->string('idkepala', 12)->primary();
            $table->string('idpengguna', 12);
            $table->string('namakepala', 100);
            $table->string('nomorinduk', 20)->nullable();
            $table->string('periodejabatan', 50)->nullable();
            $table->timestamps();
            $table->foreign('idpengguna')->references('idpengguna')->on('pengguna')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('kepalapuskesmas'); }
};