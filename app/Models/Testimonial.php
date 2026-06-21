<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Testimonial extends Model {
    protected $fillable=['customer_name','company','message_th','message_en','rating','image','is_active'];
    protected $casts=['is_active'=>'boolean'];
    public function message(){return app()->getLocale()==='en' && $this->message_en ? $this->message_en : $this->message_th;}
}
