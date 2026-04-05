<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaltePulang extends Model
{
    use HasFactory;

    protected $table = 'halte_pulang';
    protected $fillable = ['nama', 'latitude', 'longitude', 'keterangan'];
}
