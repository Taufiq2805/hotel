<?php
// app/Models/Kamar.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kamar extends Model
{
    use HasFactory;

    protected $fillable = ['nomor_kamar', 'tipe_id', 'status'];

    public function tipe()
    {
        return $this->belongsTo(TipeKamar::class, 'tipe_id');
    }
    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }
}
