<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey_ans extends Model
{
    use HasFactory;

    protected $table = 'survey_ans';

    protected $fillable = ['entry_ans', 'q_type', 'q_id', 'answer', 'status', 'c_by'];

    public function survey_prime()
    {
        return $this->belongsTo(EntrySurvey::class, 'entry_ans');
    }
    public function survey_question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'q_id');
    }
}
