<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    use HasFactory;

    // Correct table name from your DB
    protected $table = 'notification';

    protected $fillable = [
        'to_id',
        'f_id',
        'type',
        'title',
        'body',
        'c_by',
        'seen',
        'reminder',
    ];
}
