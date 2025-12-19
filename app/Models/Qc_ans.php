<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qc_ans extends Model
{
    use HasFactory;

    protected $table = 'qc_ans';

    protected $fillable = ['qc_entry', 'q_id', 'answer', 'status', 'c_by'];

    public function qc_prime()
    {
        return $this->belongsTo(EntryQC::class, 'qc_entry');
    }
}
