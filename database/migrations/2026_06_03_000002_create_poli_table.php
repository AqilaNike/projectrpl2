<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('poli', function (Blueprint $table) {
            $table->string('idpoli', 10)->primary();
            $table->string('namapoli', 100);
            $table->integer('kuotaharian')->default(30);
            $table->enum('statusbuka', ['buka', 'tutup'])->default('buka');
            $table->string('jamoperasional', 50)->default('07:30-14:00');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('poli'); }
};