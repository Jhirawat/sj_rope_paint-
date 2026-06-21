<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PayrollPeriod extends Model
{
    protected $fillable = ['name','start_date','end_date','status','paid_at','note'];
    protected $casts = ['start_date'=>'date','end_date'=>'date','paid_at'=>'datetime'];
    public function items(){ return $this->hasMany(PayrollItem::class); }
}
