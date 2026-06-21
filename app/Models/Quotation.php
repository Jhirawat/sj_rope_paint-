<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Quotation extends Model {
    protected $fillable=['quotation_no','customer_id','service_id','name','phone','line_id','email','building_type','floors','budget_range','location','province','district','subdistrict','postcode','address','map_link','latitude','longitude','details_short','details','status','estimated_price','appointment_at','admin_note'];
    protected $casts=['appointment_at'=>'datetime'];
    public function customer(){return $this->belongsTo(Customer::class);}
    public function service(){return $this->belongsTo(Service::class);}
    public function images(){return $this->hasMany(QuotationImage::class);}
}
