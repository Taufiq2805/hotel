<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- ini yang kurang

class TipeKamar extends Model
{
    use HasFactory;

    protected $table = 'tipe_kamars';

    protected $fillable = [
        'nama',
        'harga',
        'deskripsi',
        'foto', // <-- tambahkan ini
    ];
    
    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'tipe_id');
    }
}
