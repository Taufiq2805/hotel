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
         Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kamar')->unique();
            $table->foreignId('tipe_id')->constrained('tipe_kamars')->onDelete('cascade');
            $table->enum('status', ['tersedia', 'terisi', 'dibersihkan', 'maintenance'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
