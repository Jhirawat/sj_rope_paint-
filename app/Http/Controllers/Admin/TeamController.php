<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{WorkTeam,Employee};
use App\Support\AuditTrail;
use Illuminate\Http\Request;
class TeamController extends Controller
{
    public function index()
    {
        return view('admin.teams.index', [
            'teams'=>WorkTeam::with('foreman','members')->orderBy('sort_order')->get(),
            'employees'=>Employee::where('is_active',1)->orderBy('sort_order')->get(),
            'team'=>new WorkTeam(),
        ]);
    }
    public function store(Request $request)
    {
        $data=$request->validate(['name'=>'required|string|max:255','foreman_employee_id'=>'nullable|exists:employees,id','color'=>'nullable|string|max:20','member_ids'=>'array','member_ids.*'=>'exists:employees,id']);
        $team=WorkTeam::create(['name'=>$data['name'],'foreman_employee_id'=>$data['foreman_employee_id']??null,'color'=>$data['color']??'#071b35','is_active'=>true]);
        $team->members()->sync($data['member_ids'] ?? []);
        AuditTrail::log('create','teams',$team,[], $data,'สร้างทีมช่าง');
        return back()->with('success','สร้างทีมเรียบร้อย');
    }
    public function update(Request $request, WorkTeam $team)
    {
        $old=$team->toArray();
        $data=$request->validate(['name'=>'required|string|max:255','foreman_employee_id'=>'nullable|exists:employees,id','color'=>'nullable|string|max:20','is_active'=>'nullable|boolean','member_ids'=>'array','member_ids.*'=>'exists:employees,id']);
        $team->update(['name'=>$data['name'],'foreman_employee_id'=>$data['foreman_employee_id']??null,'color'=>$data['color']??'#071b35','is_active'=>$request->boolean('is_active')]);
        $team->members()->sync($data['member_ids'] ?? []);
        AuditTrail::log('update','teams',$team,$old,$team->toArray(),'แก้ไขทีมช่าง');
        return back()->with('success','อัปเดตทีมเรียบร้อย');
    }
    public function destroy(WorkTeam $team)
    {
        $old=$team->toArray();
        $team->members()->detach();
        $team->delete();
        AuditTrail::log('delete','teams',null,$old,[],'ลบทีมช่าง');
        return back()->with('success','ลบทีมแล้ว');
    }
}
