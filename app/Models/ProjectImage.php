<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    protected $fillable=['project_id','image_path','image_type','is_cover','caption_th','caption_en','sort_order'];
    protected $casts=['is_cover'=>'boolean'];
    public function project(){return $this->belongsTo(Project::class);} 
    public function caption(){ return app()->getLocale()==='en' ? ($this->caption_en ?: $this->caption_th) : ($this->caption_th ?: $this->caption_en); }
    public function typeLabel(): string { return ['cover'=>'รูปปก','before'=>'ก่อนทำ','progress'=>'ระหว่างทำ','after'=>'หลังทำ','other'=>'อื่นๆ'][$this->image_type ?? 'other'] ?? 'อื่นๆ'; }
}
