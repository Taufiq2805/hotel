<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportSewa extends Model
{
    protected $table = 'report_sewas';

    protected $fillable = ['id_reservasi'];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi')->withTrashed();
    }
}
