<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snag extends Model
{
    use HasFactory;

    public function getTable() {
        return 'snag';
    }

    protected $fillable = ['category', 'description'];

    protected $guarded = ['sale_date'];



}
