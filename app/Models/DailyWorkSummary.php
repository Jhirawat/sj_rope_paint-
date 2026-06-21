<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DailyWorkSummary extends Model
{
    protected $fillable = ['employee_id','work_order_id','work_date','work_unit','day_status','reason','approved_by','approved_at','weather_stop_photo','note'];
    protected $casts = ['work_date'=>'date','work_unit'=>'decimal:2','approved_at'=>'datetime'];
    public function employee(){ return $this->belongsTo(Employee::class); }
    public function workOrder(){ return $this->belongsTo(WorkOrder::class); }
    public function approver(){ return $this->belongsTo(User::class,'approved_by'); }
}
