<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleNotification extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function getTable() {
        return 'role_notifications';
    }

    protected $fillable = ['role_id', 'section', 'notification'];

    protected $guarded = ['sale_date'];
}
