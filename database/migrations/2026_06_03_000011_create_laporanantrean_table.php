<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('laporanantrean', function (Blueprint $table) {
            $table->string('idlaporan', 10)->primary();
            $table->string('idkepala', 12);
            $table->string('idpoli', 10);
            $table->date('tgllaporan');
            $table->integer('totalpasien')->default(0);
            $table->integer('totalselesai')->default(0);
            $table->integer('totalbatal')->default(0);
            $table->decimal('ratawaktutunggu', 10, 2)->default(0);
            $table->integer('totaltidakhadir')->default(0);
            $table->timestamps();
            $table->foreign('idkepala')->references('idkepala')->on('kepalapuskesmas')->onDelete('cascade');
            $table->foreign('idpoli')->references('idpoli')->on('poli')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('laporanantrean'); }
};