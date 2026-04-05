<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaltePergi extends Model
{
    use HasFactory;

    protected $table = 'halte_pergi';
    protected $fillable = ['nama', 'latitude', 'longitude', 'keterangan'];
}
