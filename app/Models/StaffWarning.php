<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StaffWarning extends Model
{
    protected $fillable = ['employee_id','warning_date','warning_type','title','description','deduction_units','created_by'];
    protected $casts = ['warning_date'=>'date','deduction_units'=>'decimal:2'];
    public function employee(){ return $this->belongsTo(Employee::class); }
    public function creator(){ return $this->belongsTo(User::class,'created_by'); }
}
