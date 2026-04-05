<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $table = 'bus';

    protected $fillable = [
        'nama',
        'plat_no',
        'kapasitas',
        'rute',
        'keterangan',
    ];

    public function supir()
    {
        return $this->hasMany(User::class, 'id_bus');
    }
}
