<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $table = 'progress_stage';

    protected $attributes = [
        'status' => 'Active'
    ];



    // public function act_work()
    // {
    //     return $this->hasMany(Activity_work::class, 'import_id'); // or 'activity_id' if that's the foreign key
    // }
}
