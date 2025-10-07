<?php
// app/Models/Reservasi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservasi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kamar_id',
        'nama_tamu',
        'tanggal_checkin',
        'tanggal_checkout',
        'status',
        'total',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }
}

