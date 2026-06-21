<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable=['name','phone','line_id','email','address','note'];
    public function quotations(){return $this->hasMany(Quotation::class);} 
    public function timelineNotes(){return $this->hasMany(CustomerTimelineNote::class)->latest();}
}
