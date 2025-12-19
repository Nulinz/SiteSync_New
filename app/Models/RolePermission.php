<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'role_has_permissions';

    // public function getTable() {
    //     return 'role_permissions';
    // }

    // protected $fillable = ['role_id', 'section', 'permission'];

    // protected $guarded = ['sale_date'];
}
