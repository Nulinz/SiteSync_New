<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity_material extends Model
{
    use HasFactory;

    protected $table = 'activity_material';

    protected $attributes = [
        'status' => 'Active',
    ];
}
