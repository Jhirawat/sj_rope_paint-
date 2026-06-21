<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Article extends Model {
    protected $fillable=['title_th','title_en','slug','excerpt_th','excerpt_en','content_th','content_en','cover_image','status','published_at'];
    protected $casts=['published_at'=>'datetime'];
    public function title(){return app()->getLocale()==='en' && $this->title_en ? $this->title_en : $this->title_th;}
    public function excerpt(){return app()->getLocale()==='en' && $this->excerpt_en ? $this->excerpt_en : $this->excerpt_th;}
}
