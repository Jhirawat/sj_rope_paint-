<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\{WorkOrder,WorkOrderImage,WorkOrderStatusLog,WorkOrderCheckin,AdminNotification,Quotation,Service};

class WorkOrderController extends Controller
{
    public function index(){ return view('admin.work-orders.index',['workOrders'=>WorkOrder::with('service')->latest()->paginate(20)]); }
    public function show(WorkOrder $workOrder){ return view('admin.work-orders.show',['workOrder'=>$workOrder->load('service','quotation','images','statusLogs.user','checkins.user')]); }
    public function edit(WorkOrder $workOrder){ return view('admin.work-orders.form',['workOrder'=>$workOrder->load('images','statusLogs.user','checkins.user'),'services'=>Service::orderBy('sort_order')->get()]); }
    public function update(Request $r, WorkOrder $workOrder){
        $oldStatus=$workOrder->status; $data=$this->data($r); $workOrder->update($data);
        if($oldStatus!==$workOrder->status){ WorkOrderStatusLog::create(['work_order_id'=>$workOrder->id,'user_id'=>auth()->id(),'from_status'=>$oldStatus,'to_status'=>$workOrder->status,'note'=>'เปลี่ยนสถานะจากหน้าแก้ไขใบงาน']); }
        $this->saveImages($r,$workOrder); $this->saveSignatures($r,$workOrder);
        if($r->filled('delete_images')) WorkOrderImage::whereIn('id',$r->input('delete_images',[]))->where('work_order_id',$workOrder->id)->delete();
        return back()->with('success','บันทึกใบงานแล้ว');
    }
    public function destroy(WorkOrder $workOrder){ $workOrder->delete(); return redirect()->route('admin.work-orders.index')->with('success','ลบใบงานแล้ว'); }
    public function storeFromQuotation(Quotation $quotation){
        $exists=WorkOrder::where('quotation_id',$quotation->id)->first(); if($exists) return redirect()->route('admin.work-orders.edit',$exists)->with('success','ใบงานนี้ถูกสร้างไว้แล้ว');
        $wo=WorkOrder::create(['work_order_no'=>'SJW-'.now()->format('Ymd').'-'.str_pad((string)(WorkOrder::count()+1),4,'0',STR_PAD_LEFT),'quotation_id'=>$quotation->id,'customer_id'=>$quotation->customer_id,'service_id'=>$quotation->service_id,'customer_name'=>$quotation->name,'phone'=>$quotation->phone,'line_id'=>$quotation->line_id,'email'=>$quotation->email,'address'=>$quotation->address ?: $quotation->location,'map_link'=>$quotation->map_link,'latitude'=>$quotation->latitude,'longitude'=>$quotation->longitude,'job_type'=>$quotation->building_type,'floors'=>$quotation->floors,'details'=>$quotation->details,'scheduled_survey_at'=>$quotation->appointment_at,'status'=>'pending_survey']);
        WorkOrderStatusLog::create(['work_order_id'=>$wo->id,'user_id'=>auth()->id(),'to_status'=>'pending_survey','note'=>'สร้างใบงานจากใบเสนอราคา '.$quotation->quotation_no]);
        return redirect()->route('admin.work-orders.edit',$wo)->with('success','สร้างใบงานจากใบเสนอราคาแล้ว');
    }
    public function print(WorkOrder $workOrder){ return view('admin.work-orders.print',['workOrder'=>$workOrder->load('service','images','statusLogs.user','checkins.user')]); }
    public function checkin(Request $r, WorkOrder $workOrder){
        $data=$r->validate(['latitude'=>'nullable|numeric|between:-90,90','longitude'=>'nullable|numeric|between:-180,180','note'=>'nullable|string|max:1000']);
        WorkOrderCheckin::create(array_merge($data,['work_order_id'=>$workOrder->id,'user_id'=>auth()->id()]));
        $workOrder->update(['last_checkin_at'=>now(),'last_checkin_latitude'=>$data['latitude']??null,'last_checkin_longitude'=>$data['longitude']??null]);
        return back()->with('success','เช็คอินหน้างานแล้ว');
    }
    public function sign(Request $r, WorkOrder $workOrder){
        $data=$r->validate(['role'=>'required|in:customer,foreman,inspector','signature_data'=>'required|string']);
        abort_unless(str_starts_with($data['signature_data'],'data:image/png;base64,'),422,'รูปแบบลายเซ็นไม่ถูกต้อง');
        $raw=base64_decode(substr($data['signature_data'],22)); abort_if(strlen($raw)>1024*1024,422,'ลายเซ็นมีขนาดใหญ่เกินไป');
        $path='work-order-signatures/'.$workOrder->id.'-'.$data['role'].'-'.time().'.png'; Storage::disk('public')->put($path,$raw);
        $field=['customer'=>'customer_signature_path','foreman'=>'foreman_signature_path','inspector'=>'inspector_signature_path'][$data['role']];
        $workOrder->update([$field=>$path, 'accepted_at'=>$data['role']==='customer' ? now() : $workOrder->accepted_at]);
        return back()->with('success','บันทึกลายเซ็นแล้ว');
    }
    private function data(Request $r): array { return $r->validate(['service_id'=>'nullable|exists:services,id','customer_name'=>'required|max:255','phone'=>'required|max:30','line_id'=>'nullable|max:100','email'=>'nullable|email|max:255','address'=>'nullable|string','map_link'=>'nullable|max:500','latitude'=>'nullable|numeric|between:-90,90','longitude'=>'nullable|numeric|between:-180,180','job_type'=>'nullable|max:255','floors'=>'nullable|integer|min:1|max:200','details'=>'nullable|string','team_leader'=>'nullable|max:255','team_members'=>'nullable|string','scheduled_survey_at'=>'nullable|date','start_at'=>'nullable|date','finish_at'=>'nullable|date','status'=>'required|in:pending_survey,surveyed,waiting_start,working,inspection,completed,cancelled','admin_note'=>'nullable|string','images.*'=>'nullable|image|max:5120','image_types.*'=>'nullable|in:before,during,after,other','image_captions.*'=>'nullable|max:255']); }
    private function saveImages(Request $r, WorkOrder $wo): void { foreach($r->file('images',[]) as $i=>$file){ $wo->images()->create(['image_path'=>$file->store('work-orders','public'),'type'=>$r->input('image_types.'.$i,'other'),'caption'=>$r->input('image_captions.'.$i),'sort_order'=>$i]); } }
    private function saveSignatures(Request $r, WorkOrder $wo): void { foreach(['customer_signature'=>'customer_signature_path','foreman_signature'=>'foreman_signature_path','inspector_signature'=>'inspector_signature_path'] as $input=>$field){ if($r->hasFile($input)){ $wo->update([$field=>$r->file($input)->store('work-order-signatures','public')]); } } }
}
