<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $fillable = ['payroll_period_id','employee_id','daily_wage','work_units','gross_amount','bonus_amount','deduction_amount','advance_amount','net_amount','late_count','warning_count','note'];
    protected $casts = ['daily_wage'=>'decimal:2','work_units'=>'decimal:2','gross_amount'=>'decimal:2','bonus_amount'=>'decimal:2','deduction_amount'=>'decimal:2','advance_amount'=>'decimal:2','net_amount'=>'decimal:2','late_count'=>'integer','warning_count'=>'integer'];
    public function period(){ return $this->belongsTo(PayrollPeriod::class,'payroll_period_id'); }
    public function employee(){ return $this->belongsTo(Employee::class); }
}
