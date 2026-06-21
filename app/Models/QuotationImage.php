<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuotationImage extends Model { protected $fillable=['quotation_id','image_path','original_name']; public function quotation(){return $this->belongsTo(Quotation::class);} }
