<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendala extends Model
{
    use HasFactory;

    protected $table = 'kendala';

    protected $fillable = [
        'id_bus',
        'id_supir',
        'keterangan',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'id_bus');
    }

    public function supir()
    {
        return $this->belongsTo(User::class, 'id_supir');
    }
}
