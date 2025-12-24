<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function getTable()
    {
        return 'task';
    }

    protected $fillable = [
        'title',
        'project_id',
        'assigned_to',
        'priority',
        'end_timestamp',
        'description',
        'file_attachment',
        'status',
        'file_name',
        'created_by',
        'parent_task_id',
        'is_assigned'
    ];

    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function created_user()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id')
            ->where('id', '!=', 1);
    }

    public function children()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    public function subTasks()
    {
        return $this->hasMany(Task::class, 'is_assigned', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id');
    }

    protected $guarded = ['sale_date'];
}
