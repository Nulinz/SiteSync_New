<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';

    // public function getTable() {
    //     return 'role';
    // }

    public function permissions()
    {
        return $this->belongsToMany(RolePermission::class, 'role_id');
    }

    // protected $fillable = ['project_id', 'employee_id', 'role_title'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // protected $guarded = ['sale_date'];
}
