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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nik', 16)->unique()->nullable();
            $table->string('no_bpjs', 20)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('password');
            $table->enum('role', ['patient', 'admin', 'doctor'])->default('patient');
            $table->string('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
