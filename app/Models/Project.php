<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable=['service_id','source_work_order_id','title_th','title_en','slug','location_th','location_en','description_th','description_en','before_image','after_image','status','budget','project_date','started_at','finished_at','is_featured','is_active','sort_order'];
    protected $casts=['is_featured'=>'boolean','is_active'=>'boolean','project_date'=>'date','started_at'=>'date','finished_at'=>'date'];
    public function service(){return $this->belongsTo(Service::class);} 
    public function images(){return $this->hasMany(ProjectImage::class)->orderBy('sort_order');}
    public function coverImage(){return $this->hasOne(ProjectImage::class)->where('is_cover',true)->latestOfMany();}
    public function beforeImages(){return $this->hasMany(ProjectImage::class)->where('image_type','before')->orderBy('sort_order');}
    public function progressImages(){return $this->hasMany(ProjectImage::class)->where('image_type','progress')->orderBy('sort_order');}
    public function afterImages(){return $this->hasMany(ProjectImage::class)->where('image_type','after')->orderBy('sort_order');}
    public function title(){return app()->getLocale()==='en'?($this->title_en?:$this->title_th):($this->title_th?:$this->title_en);} 
    public function location(){return app()->getLocale()==='en'?($this->location_en?:$this->location_th):($this->location_th?:$this->location_en);} 
    public function description(){return app()->getLocale()==='en'?($this->description_en?:$this->description_th):($this->description_th?:$this->description_en);} 
    public function imageCount(string $type=null): int { return $type ? $this->images->where('image_type',$type)->count() : $this->images->count(); }
}
