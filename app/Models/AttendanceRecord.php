<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = ['employee_id','work_order_id','work_date','check_in_at','check_out_at','check_in_photo','check_out_photo','check_in_latitude','check_in_longitude','check_out_latitude','check_out_longitude','morning_location_type','late_minutes','is_late','status','note'];
    protected $casts = ['work_date'=>'date','check_in_at'=>'datetime','check_out_at'=>'datetime','is_late'=>'boolean','late_minutes'=>'integer','check_in_latitude'=>'decimal:7','check_in_longitude'=>'decimal:7','check_out_latitude'=>'decimal:7','check_out_longitude'=>'decimal:7'];
    public function employee(){ return $this->belongsTo(Employee::class); }
    public function workOrder(){ return $this->belongsTo(WorkOrder::class); }
}
