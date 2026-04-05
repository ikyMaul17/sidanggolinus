<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'laporan';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_bus',
        'nilai_fuzzy',
        'kategori_prioritas',
        'status_perbaikan',
        'id_pertanyaan',
        'target',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'id_bus');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'id_pertanyaan');
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanLaporan::class, 'laporan_id');
    }
}
