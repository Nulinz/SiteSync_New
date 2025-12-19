<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function getTable() {
        return 'category';
    }

    protected $fillable = ['category', 'category_title', 'category_description'];

    protected $guarded = ['sale_date'];
}
