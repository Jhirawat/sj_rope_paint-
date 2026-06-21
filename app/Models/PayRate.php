<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PayRate extends Model
{
    public function employees(){ return $this->hasMany(Employee::class,'default_pay_rate_id'); }
    protected $fillable = ['name','amount','is_active','sort_order'];
    protected $casts = ['amount'=>'decimal:2','is_active'=>'boolean'];
}
