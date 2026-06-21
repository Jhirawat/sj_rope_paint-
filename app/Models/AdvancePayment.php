<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    protected $fillable = ['employee_id','requested_by','approved_by','amount','is_special_request','special_request_note','request_date','status','reason','approved_at','note'];
    protected $casts = ['amount'=>'decimal:2','is_special_request'=>'boolean','request_date'=>'date','approved_at'=>'datetime'];
    public function employee(){ return $this->belongsTo(Employee::class); }
    public function requester(){ return $this->belongsTo(User::class,'requested_by'); }
    public function approver(){ return $this->belongsTo(User::class,'approved_by'); }
}
