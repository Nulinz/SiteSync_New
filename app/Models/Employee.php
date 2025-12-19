<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\New\Role as NewRole;
use Spatie\Permission\Models\Role;
use App\Models\{Designation, Master_role, Project};
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

class Employee extends Authenticatable
{
    use HasFactory, HasApiTokens, HasRoles;

    // protected static function booted()
    // {
    //     static::addGlobalScope('active', function (Builder $builder) {
    //         $builder->where('status', 'active');
    //     });
    // }

    public function getTable()
    {
        return 'employee';
    }

    protected $fillable = ['employee_code', 'name', 'password', 'contact_number', 'email_id', 'role_id', 'image_path', 'auth_token', 'token'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function m_role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function assignedProjects()
    {
        return $this->hasMany(Project::class, 'assigned_to');
    }


    protected $guarded = ['sale_date'];
}
