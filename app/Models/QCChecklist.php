<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QCChecklist extends Model
{
    use HasFactory;

    public function getTable()
    {
        return 'qc_checklist';
    }

    protected $fillable = ['qc_id', 'question'];

    protected $guarded = ['sale_date'];
}
