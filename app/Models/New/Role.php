<?php

namespace App\Models\New;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function getTable()
    {
        return 'roles';
    }

    protected $fillable = ['description', 'role_title'];
}
