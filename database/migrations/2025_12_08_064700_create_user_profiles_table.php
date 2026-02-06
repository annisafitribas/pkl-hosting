<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            // FK ke users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // FK ke bagian dan pembimbing (nullable)
            $table->foreignId('bagian_id')->nullable()->constrained('bagians')->onDelete('set null');
            $table->foreignId('pembimbing_id')->nullable()->constrained('pembimbing_profiles')->onDelete('set null');

            // Field tambahan
            $table->string('nomor_induk')->nullable();
            $table->string('tingkatan')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('kelas')->nullable();
            $table->string('jurusan')->nullable();

            // Tanggal magang
            $table->date('tgl_masuk')->nullable();
            $table->date('tgl_keluar')->nullable();

            // Status magang tersimpan
            $table->string('status_magang')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};