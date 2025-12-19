<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snag_ans extends Model
{
    use HasFactory;


    protected $table = 'snag_ans';

    protected $fillable = ['entry_snag','file','status','c_by'];

}
