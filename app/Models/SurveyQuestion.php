<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    public function getTable()
    {
        return 'survey_questions';
    }

    protected $fillable = ['survey_id', 'question', 'question_type'];

    public function choices()
    {
        return $this->hasMany(SurveyChoice::class, 'question_id');
    }
    public function Survey_details()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
}
