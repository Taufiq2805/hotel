<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengeluaran; // pastikan di-import

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'kamar_id',
        'user_id',
        'tanggal',
        'status',
        'catatan',
    ];

    // ===== RELASI =====
    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengeluaran()
    {
        return $this->hasOne(Pengeluaran::class);
    }

    // ===== EVENT OTOMATIS SAAT MAINTENANCE DIBUAT =====
    protected static function booted()
    {
        static::created(function ($maintenance) {
            // Kalau ada catatan (misalnya barang rusak), otomatis buat pengeluaran
            if (!empty($maintenance->catatan)) {
                Pengeluaran::create([
                    'kamar_id' => $maintenance->kamar_id,
                    'maintenance_id' => $maintenance->id,
                    'tanggal_pengeluaran' => now(),
                    'nama_barang' => $maintenance->catatan,
                    'jumlah_barang' => 0,
                    'harga_satuan' => 0, // bisa diedit nanti di halaman pengeluaran
                    'total_harga' => 0,
                    'created_by' => $maintenance->user_id,
                ]);
            }
        });
    }
}
