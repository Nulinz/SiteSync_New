<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EntrySnag extends Model
{
    use HasFactory;

    protected $table = 'entry_snag';

    protected $fillable = [
        'category_id',
        'description',
        'assigned_to',
        'due_date',
        'file_attachment',
        'file_name',
        'project_id',
        'location',
        'approved_by',
        'c_by'
    ];

    public function snag()
    {
        return $this->belongsTo(Snag::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function user_cby()
    {
        return $this->belongsTo(Employee::class, 'c_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function snag_ans()
    {
        return $this->hasMany(Snag_ans::class, 'entry_snag');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
