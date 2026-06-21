<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WorkTeam extends Model
{
    protected $fillable=['name','foreman_employee_id','color','is_active','sort_order'];
    protected $casts=['is_active'=>'boolean'];
    public function foreman(){ return $this->belongsTo(Employee::class,'foreman_employee_id'); }
    public function members(){ return $this->belongsToMany(Employee::class,'employee_work_team')->withPivot('role_in_team')->withTimestamps(); }
    public function workOrders(){ return $this->hasMany(WorkOrder::class); }
}
