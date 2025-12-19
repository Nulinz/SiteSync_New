<?php

namespace App\Models\New;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function getTable()
    {
        return 'role_has_permissions';
    }

    // protected $fillable = ['role_id', 'permission'];

}
