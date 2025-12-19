<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drawing extends Model
{
    use HasFactory;

    public function getTable()
    {
        return 'drawing';
    }

    protected $fillable = ['title', 'file_type', 'description'];

    protected $guarded = ['sale_date'];

    // Relationships
    public function ent_drawing()
    {
        return $this->belongsTo(EntryDrawing::class, 'id');
    }

    public function latestEntry($projectId)
    {
        return $this->hasOne(EntryDrawing::class, 'drawing_id', 'id')
            ->where('project_id', $projectId)
            ->where('is_draft', 0)
            ->whereIn('status', ['Approved', 'pending'])
            ->latest('id');
    }

}
