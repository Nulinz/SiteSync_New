<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'progress_import';

    protected $attributes = [
        'status' => 'Active'
    ];

    // protected $fillable = [
    //     'pro_id',
    //     'stage',
    //     'sub',
    //     'duration',
    //     'st_date',
    //     'end_date'
    // ];

    public function act_work()
    {
        return $this->hasMany(Activity_work::class, 'import_id'); // or 'activity_id' if that's the foreign key
    }
}
