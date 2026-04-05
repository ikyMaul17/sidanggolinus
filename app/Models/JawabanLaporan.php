<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanLaporan extends Model
{
    protected $table = 'jawaban_laporan';

    protected $fillable = [
        'laporan_id',
        'user_id',
        'nilai',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
