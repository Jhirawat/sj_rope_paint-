<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CustomerTimelineNote extends Model
{
    protected $fillable = ['customer_id','quotation_id','work_order_id','note_type','title','note','follow_up_at','created_by'];
    protected $casts = ['follow_up_at'=>'datetime'];
    public function customer(){ return $this->belongsTo(Customer::class); }
    public function quotation(){ return $this->belongsTo(Quotation::class); }
    public function workOrder(){ return $this->belongsTo(WorkOrder::class); }
}
