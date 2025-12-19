<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SnagComment extends Model
{
    protected $table = 'snag_comments';
    
    protected $fillable = [
        'snag_id',
        'user_id', 
        'comment',
        'created_at',
        'updated_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }

    public function snag()
    {
        return $this->belongsTo(EntrySnag::class, 'snag_id', 'id');
    }
}