<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    public function getTable() {
        return 'sub_category';
    }

    protected $fillable = ['category_id', 'sub_category', 'sub_category_title', 'sub_category_description'];

    protected $guarded = ['sale_date'];
}
