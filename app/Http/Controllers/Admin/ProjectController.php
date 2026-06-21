<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{Project,Service,ProjectImage,WorkOrder};

class ProjectController extends Controller
{
    public function index(){
        return view('admin.projects.index',['projects'=>Project::with(['service','images'])->orderBy('sort_order')->latest()->paginate(20)]);
    }
    public function create(){
        return view('admin.projects.form',['project'=>new Project,'services'=>Service::orderBy('title_th')->get()]);
    }
    public function store(Request $r){
        $project=Project::create($this->data($r));
        $this->saveImageGroup($r,$project,'cover_image','cover',true);
        $this->saveImageGroup($r,$project,'before_images','before');
        $this->saveImageGroup($r,$project,'progress_images','progress');
        $this->saveImageGroup($r,$project,'after_images','after');
        return redirect()->route('admin.projects.edit',$project)->with('success','เพิ่มผลงานแล้ว สามารถจัดรูปก่อนทำ/ระหว่างทำ/หลังทำต่อได้');
    }
    public function edit(Project $project){
        $project->load('images','service');
        return view('admin.projects.form',['project'=>$project,'services'=>Service::orderBy('title_th')->get()]);
    }
    public function update(Request $r, Project $project){
        $project->update($this->data($r,$project->id));
        $this->saveImageGroup($r,$project,'cover_image','cover',true);
        $this->saveImageGroup($r,$project,'before_images','before');
        $this->saveImageGroup($r,$project,'progress_images','progress');
        $this->saveImageGroup($r,$project,'after_images','after');
        if($r->filled('delete_images')){
            ProjectImage::whereIn('id',$r->input('delete_images',[]))->where('project_id',$project->id)->delete();
        }
        if($r->filled('set_cover_image_id')){
            $project->images()->update(['is_cover'=>false]);
            $project->images()->where('id',$r->set_cover_image_id)->update(['is_cover'=>true,'image_type'=>'cover']);
        }
        return back()->with('success','บันทึกผลงานแล้ว');
    }
    public function destroy(Project $project){$project->delete(); return back()->with('success','ลบผลงานแล้ว');}
    public function moveUp(Project $project){$prev=Project::where('sort_order','<',$project->sort_order)->orderByDesc('sort_order')->first(); if($prev){[$project->sort_order,$prev->sort_order]=[$prev->sort_order,$project->sort_order]; $project->save(); $prev->save();} return back()->with('success','เลื่อนผลงานขึ้นแล้ว');}
    public function moveDown(Project $project){$next=Project::where('sort_order','>',$project->sort_order)->orderBy('sort_order')->first(); if($next){[$project->sort_order,$next->sort_order]=[$next->sort_order,$project->sort_order]; $project->save(); $next->save();} return back()->with('success','เลื่อนผลงานลงแล้ว');}
    public function createFromWorkOrder(WorkOrder $workOrder){
        $project=Project::create([
            'source_work_order_id'=>$workOrder->id,
            'service_id'=>$workOrder->service_id,
            'title_th'=>'ผลงาน '.$workOrder->work_order_no,
            'title_en'=>'Project '.$workOrder->work_order_no,
            'slug'=>Str::slug('project-'.$workOrder->work_order_no.'-'.time()),
            'location_th'=>$workOrder->address,
            'location_en'=>$workOrder->address,
            'description_th'=>$workOrder->details,
            'description_en'=>$workOrder->details,
            'project_date'=>$workOrder->finish_at ?: now(),
            'status'=>'completed','is_active'=>false,'sort_order'=>Project::max('sort_order')+1,
        ]);
        foreach($workOrder->images as $img){
            $project->images()->create(['image_path'=>$img->image_path,'image_type'=>['during'=>'progress'][$img->type] ?? $img->type,'caption_th'=>$img->caption,'caption_en'=>$img->caption,'sort_order'=>$img->sort_order]);
        }
        return redirect()->route('admin.projects.edit',$project)->with('success','สร้างผลงานจากใบงานแล้ว กรุณาตรวจข้อมูลและกดเผยแพร่');
    }
    private function data(Request $r,$id=null){
        $d=$r->validate([
            'service_id'=>'nullable|exists:services,id','title_th'=>'required|max:255','title_en'=>'nullable|max:255','slug'=>'nullable|max:255|unique:projects,slug,'.($id?:'NULL').',id','location_th'=>'nullable|max:255','location_en'=>'nullable|max:255','description_th'=>'nullable','description_en'=>'nullable','status'=>'required|in:planned,working,completed,cancelled','budget'=>'nullable|numeric|min:0','project_date'=>'nullable|date','sort_order'=>'nullable|integer','started_at'=>'nullable|date','finished_at'=>'nullable|date','is_featured'=>'nullable|boolean','is_active'=>'nullable|boolean','cover_image'=>'nullable|image|max:5120','before_images.*'=>'nullable|image|max:5120','progress_images.*'=>'nullable|image|max:5120','after_images.*'=>'nullable|image|max:5120'
        ]);
        $d['slug']=$d['slug']?:Str::slug($d['title_en']?:$d['title_th'].'-'.time());
        $d['is_featured']=$r->boolean('is_featured'); $d['is_active']=$r->boolean('is_active');
        $d['sort_order']=$d['sort_order'] ?? (Project::max('sort_order')+1);
        return $d;
    }
    private function saveImageGroup(Request $r, Project $project, string $field, string $type, bool $cover=false): void {
        $files=$cover ? ($r->hasFile($field) ? [$r->file($field)] : []) : $r->file($field,[]);
        if($cover && $files){ $project->images()->update(['is_cover'=>false]); }
        foreach($files as $i=>$file){
            $project->images()->create(['image_path'=>$file->store('projects','public'),'image_type'=>$type,'is_cover'=>$cover,'sort_order'=>($project->images()->where('image_type',$type)->max('sort_order') ?? 0)+$i+1]);
        }
    }
}
