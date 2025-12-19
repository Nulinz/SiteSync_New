<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\QCChecklist;
use Carbon\Carbon;

class EntryQC extends Model
{
    use HasFactory;

    protected $table = 'entry_qc';  // Table name

    protected $fillable = [
        'qc_title',
        'checklist',
        'assigned_to',
        'due_date',
        'file_attachment',
        'file_name',
        'project_id',
        'approved_by',
        'c_by'
    ];

    protected $casts = [
        'checklist' => 'array',
    ];

    public function qc()
    {
        return $this->belongsTo(QC::class, 'qc_title');
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function getChecklistNamesAttribute()
    {
        $checklistIds = $this->checklist ?? []; // Already casted to an array
        return QCChecklist::whereIn('id', $checklistIds)->pluck('question', 'id')->toArray();
    }



    public function user_cby()
    {
        return $this->belongsTo(Employee::class, 'c_by');
    }

    public function qc_ans()
    {
        return $this->hasMany(Qc_ans::class, 'qc_entry');
    }

    // public function qc_qst()
    // {
    //     return $this->belongsTo(QCChecklist::class, 'c_by');
    // }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
