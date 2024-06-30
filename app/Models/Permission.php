<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;


    // app/Models/Permission.php
public function user()
{
    return $this->belongsTo(User::class, 'employee_id', 'id');
}

public function attendance()
{
    return $this->belongsTo(Attendance::class, 'employee_id', 'id');
}

}
