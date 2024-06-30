<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Employee;
use App\Models\Attendance;

class Employee extends Model
{
    use HasFactory;

   protected $fillable = [
    'name',
    'email',
    'password',
    'position',

   ];

   public function attendances()
   {
    return $this->hasMany(Attendance::class, 'employee_id', 'id');
   }
   
    
    

}
