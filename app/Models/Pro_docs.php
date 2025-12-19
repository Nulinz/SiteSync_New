<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pro_docs extends Model
{
    use HasFactory;

    protected $table = 'pro_docs';

    protected $fillable = ['pro_id','type','title','desp','link','status'];
}
