<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['user_id','name','nickname','employee_type','position_note','phone','profile_photo','default_pay_rate_id','daily_wage','is_active','employment_status','sort_order','staff_score'];
    protected $casts = ['daily_wage'=>'decimal:2','is_active'=>'boolean'];
    public function user(){ return $this->belongsTo(User::class); }
    public function payRate(){ return $this->belongsTo(PayRate::class,'default_pay_rate_id'); }
    public function attendanceRecords(){ return $this->hasMany(AttendanceRecord::class); }
    public function dailyWorkSummaries(){ return $this->hasMany(DailyWorkSummary::class); }
    public function advancePayments(){ return $this->hasMany(AdvancePayment::class); }
    public function teams(){ return $this->belongsToMany(WorkTeam::class,'employee_work_team')->withPivot('role_in_team')->withTimestamps(); }
}
