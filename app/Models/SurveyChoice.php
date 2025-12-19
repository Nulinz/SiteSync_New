<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyChoice extends Model
{
    use HasFactory;

	public function getTable() {
        return 'survey_choices';
    }

    public function surveyQuestion()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    protected $fillable = ['question_id', 'choice'];
}

