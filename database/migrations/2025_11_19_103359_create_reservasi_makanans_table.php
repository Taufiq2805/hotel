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
        Schema::create('reservasi_makanans', function (Blueprint $table) {
            $table->id();

            // Relasi ke reservasi
            $table->foreignId('reservasi_id')
                ->constrained('reservasis')
                ->onDelete('cascade');

            // Relasi ke makanan
            $table->foreignId('makanan_id')
                ->constrained('makanans')
                ->onDelete('cascade');

            // Jumlah makanan yang dipesan
            $table->integer('qty')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi_makanans');
    }
};
