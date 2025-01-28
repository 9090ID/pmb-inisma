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
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('sertifikat')->nullable(); // Menyimpan path file sertifikat
            $table->string('jumlah_juz')->nullable(); // Menyimpan jumlah juz (sebagai string, karena opsional)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn('sertifikat');
            $table->dropColumn('jumlah_juz');
        });
    }
};
