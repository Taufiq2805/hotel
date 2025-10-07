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
         Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kamar_id')->nullable()->constrained('kamars')->onDelete('set null');
            $table->foreignId('maintenance_id')->nullable()->constrained('maintenances')->onDelete('set null');
            $table->date('tanggal_pengeluaran');
            $table->string('nama_barang');
            $table->integer('jumlah_barang');
            $table->integer('harga_satuan');
            $table->integer('total_harga');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
