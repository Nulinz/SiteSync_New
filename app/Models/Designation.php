<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    public function getTable() {
        return 'designation';
    }

    protected $fillable = ['department_id', 'title', 'description'];

    protected $guarded = ['sale_date'];

    // public function designation()
    // {
    //     return $this->belongsTo(Designation::class, 'designation_id');
    // }
}
