<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    protected $fillable = ['service_id','image_path','caption_th','caption_en','sort_order'];
    public function service(){ return $this->belongsTo(Service::class); }
    public function caption(){ return app()->getLocale()==='en' ? ($this->caption_en ?: $this->caption_th) : ($this->caption_th ?: $this->caption_en); }
}
