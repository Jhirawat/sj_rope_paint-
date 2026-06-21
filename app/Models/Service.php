<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Service extends Model {
    protected $fillable=['title_th','title_en','slug','excerpt_th','excerpt_en','content_th','content_en','icon','image','sort_order','is_active'];
    protected $casts=['is_active'=>'boolean'];
    public function projects(){return $this->hasMany(Project::class);} public function images(){return $this->hasMany(ServiceImage::class)->orderBy("sort_order");}
    public function title(){return app()->getLocale()==='en' && $this->title_en ? $this->title_en : $this->title_th;}
    public function excerpt(){return app()->getLocale()==='en' && $this->excerpt_en ? $this->excerpt_en : $this->excerpt_th;}
}
