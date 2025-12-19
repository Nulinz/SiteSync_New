<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity_work extends Model
{
    use HasFactory;

    protected $table = 'activity_work';

    protected $attributes = [
        'status' => 'save'
    ];

    protected $fillable = [
        'import_id'
    ];

    protected $casts = [
        'file' => 'array',
        'qc' => 'array'
    ];

    // App\Models\Activity_work.php
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'import_id'); // or 'activity_id'
    }
}
