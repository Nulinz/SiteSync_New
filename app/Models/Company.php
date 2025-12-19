<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function getTable() {
        return 'company';
    }

    protected $fillable = ['logo', 'address', 'district', 'state', 'pincode', 'gst_number', 'pan_number', 'msme_number', 'gst_attachment', 'pancard_attachment', 'msme_attachment', 'bank_name', 'account_holder_name', 'account_number', 'ifsc_code','name', 'branch_name'];

    protected $guarded = ['sale_date'];
}
