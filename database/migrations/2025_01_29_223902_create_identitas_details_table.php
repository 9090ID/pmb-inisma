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
        Schema::create('identitas_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained()->onDelete('cascade');
            $table->string('NIK')->unique();
            $table->string('NISN')->unique()->nullable();
            $table->string('asalsekolah');
            $table->string('tempatlahir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identitas_details');
    }
};
