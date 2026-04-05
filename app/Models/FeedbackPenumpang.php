<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackPenumpang extends Model
{
    use HasFactory;

    protected $table = 'feedback_penumpang';

    protected $fillable = [
        'user_input',
        'tipe',
        'rating',
        'pesan',
    ];
}
