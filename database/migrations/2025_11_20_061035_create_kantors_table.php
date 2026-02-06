<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kantors', function (Blueprint $table) {
            $table->id();

            // informasi utama
            $table->string('nama_apk')->nullable();
            $table->string('nama_pt')->nullable();
            $table->string('logo')->nullable();
            $table->text('alamat')->nullable();
            $table->string('link_maps')->nullable();

            // kontak & sosial media
            $table->string('wa_link')->nullable();
            $table->string('ig_link')->nullable();

            // hari kerja (default: Senin–Jumat)
            $table->json('hari_kerja')->default(json_encode(['senin','selasa','rabu','kamis','jumat']));

            // jam kerja
            $table->time('jam_masuk')->default('07:45:00');
            $table->time('jam_keluar')->default('17:00:00');

            // lokasi & radius absen
            $table->decimal('kantor_lat', 11, 8)->nullable();
            $table->decimal('kantor_lng', 11, 8)->nullable();
            $table->integer('radius_absen')->default(100);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kantors');
    }
};
