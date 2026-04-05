<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackSupir extends Model
{
    use HasFactory;

    protected $table = 'feedback_supir';

    protected $fillable = [
        'user_input',
        'tipe',
        'rating',
        'pesan',
    ];
}
