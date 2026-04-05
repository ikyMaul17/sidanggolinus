<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'pertanyaan';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kategori',
        'teks_pertanyaan',
        'status',
        'target_pengguna',
    ];

    protected $casts = [
        'target_pengguna' => 'array',
    ];

    public function bus()
    {
        return $this->belongsToMany(Bus::class, Laporan::class, 'id_pertanyaan', 'id_bus');
    }
}
