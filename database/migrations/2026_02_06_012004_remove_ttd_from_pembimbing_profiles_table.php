<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembimbing_profiles', function (Blueprint $table) {
            $table->dropColumn('ttd');
        });
    }

    public function down(): void
    {
        Schema::table('pembimbing_profiles', function (Blueprint $table) {
            $table->string('ttd')->nullable();
        });
    }
};
