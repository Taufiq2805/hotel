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
    Schema::create('reservasis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kamar_id')->constrained('kamars')->onDelete('cascade');
        $table->string('nama_tamu');
        $table->date('tanggal_checkin');
        $table->date('tanggal_checkout');
        $table->enum('status', ['tersedia', 'terisi', 'dibersihkan', 'maintenance', 'selesai'])->default('tersedia');
        $table->integer('total')->nullable();
        $table->timestamps();
        $table->softDeletes(); // ← tambahkan ini
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
