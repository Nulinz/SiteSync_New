<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comment';

    // Comment.php
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    // Comment.php
    public function comment_for()
    {
        return $this->belongsTo(Employee::class, 'comment_for');
    }
    // Comment.php
    public function created_user()
    {
        return $this->belongsTo(Employee::class, 'c_by');
    }
}
