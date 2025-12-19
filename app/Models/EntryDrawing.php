<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryDrawing extends Model
{
    use HasFactory;

    protected $table = 'entry_drawing';

    protected $fillable = [
        'drawing_id',
        'uploaded_by',
        'uploaded_on',
        'file_attachment',
        'file_name',
        'status',
        'project_id',
        'approved_by',
        'version',
        'is_draft',
        'c_by'
    ];

    // Relationships
    public function drawing()
    {
        return $this->belongsTo(Drawing::class, 'drawing_id');
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'uploaded_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
