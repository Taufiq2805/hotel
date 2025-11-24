<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Makanan extends Model
{
     use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'foto',
    ];

    public function reservasis()
{
    return $this->belongsToMany(Reservasi::class, 'reservasi_makanans')
                ->withPivot('qty');
}

}
