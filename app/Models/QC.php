<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\QCChecklist;

class QC extends Model
{
    use HasFactory;

    public function getTable() {
        return 'qc';
    }

    public function checklists()
    {
        return $this->hasMany(QCChecklist::class, 'qc_id');
    }

    protected $fillable = ['title', 'description'];

    protected $guarded = ['sale_date'];

    
}
