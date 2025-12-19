<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EntrySurvey extends Model
{
    use HasFactory;

    protected $table = 'entry_survey';

    protected $fillable = [
        'survey_id',
        'instruction',
        'assigned_to',
        'due_date',
        'file_attachment',
        'project_id',
        'approved_by',
        'c_by'
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function user_cby()
    {
        return $this->belongsTo(Employee::class, 'c_by');
    }
    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function survey_ans()
    {
        return $this->hasMany(Survey_ans::class, 'entry_ans');
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
