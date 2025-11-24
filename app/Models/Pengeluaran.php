<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kamar_id',
        'maintenance_id',
        'tanggal_pengeluaran',
        'nama_barang',
        'jumlah_barang',
        'harga_satuan',
        'total_harga',
        'created_by',
    ];

    // Relasi ke Kamar (nullable)
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    // Relasi ke Maintenance (nullable)
    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }

    // Relasi ke User pencatat
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    protected static function booted()
{
    static::created(function ($pengeluaran) {
        // Jika pengeluaran punya relasi ke maintenance dan kamar
        if ($pengeluaran->maintenance && $pengeluaran->kamar) {
            // Ubah status maintenance ke 'selesai'
            $pengeluaran->maintenance->update(['status' => 'selesai']);

            // Ubah status kamar ke 'tersedia'
            $pengeluaran->kamar->update(['status' => 'tersedia']);
        }
    });
}

}
