<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function getTable()
    {
        return 'projects';
    }

    protected $fillable = ['project_name', 'project_id', 'client_name', 'contact_number', 'alternate_contact_number', 'email_id', 'address', 'city', 'state', 'pincode', 'pro_address', 'pro_city', 'pro_state', 'pro_pincode', 'plot_size', 'plot_unit', 'total_building_area', 'building_area_unit', 'assigned_to', 'file_attachment', 'file_name', 'c_by'];

    protected $casts = [
        'assigned_to' => 'array',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(Employee::class, 'assigned_to');
    // }

    public function getAssignedUsersAttribute()
    {
        return Employee::whereIn('id', $this->assigned_to)->select('id', 'name', 'image_path')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'image_path' => $user->image_path ?  asset('img/employees/' . $user->image_path) : null, // Convert to full URL
            ];
        });;
    }


    protected $guarded = ['sale_date'];
}
