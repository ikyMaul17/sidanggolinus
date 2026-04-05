<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'id_bus');
    }

    public function penumpang()
    {
        return $this->belongsTo(User::class, 'id_penumpang');
    }

    public function penjemputan()
    {
        return $this->belongsTo(HaltePergi::class, 'id_penjemputan');
    }

    public function tujuan()
    {
        return $this->belongsTo(HaltePulang::class, 'id_tujuan');
    }
}
