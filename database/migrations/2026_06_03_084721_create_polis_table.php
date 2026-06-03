<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('polis', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // e.g. Poli Umum
            $table->string('kode', 5); // e.g. A, G, K
            $table->string('lokasi')->nullable(); // e.g. Lt. 1 Gedung A
            $table->string('icon')->default('medical_services');
            $table->boolean('is_active')->default(true);
            $table->integer('kuota_harian')->default(30);
            $table->time('jam_buka')->default('07:30:00');
            $table->time('jam_tutup')->default('14:00:00');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polis');
    }
};
