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
         Schema::create('maintenances', function (Blueprint $table) {
            $table->id();

            // Relasi ke kamar
            $table->foreignId('kamar_id')->constrained('kamars')->onDelete('cascade');
            // Relasi ke user (siapa yang membuat/menangani)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal'); // Tanggal maintenance
            $table->enum('status', ['tersedia', 'terisi', 'dibersihkan', 'maintenance'])->default('maintenance'); // Status maintenance
            $table->text('catatan')->nullable(); // Catatan tambahan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
