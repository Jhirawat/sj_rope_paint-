<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use Illuminate\Http\Request; use App\Models\Quotation;
class QuotationController extends Controller {
 public function index(Request $r){ $quotes=Quotation::with('service')->when($r->status,fn($q,$s)=>$q->where('status',$s))->latest()->paginate(20); return view('admin.quotations.index',compact('quotes')); }
 public function show(Quotation $quotation){ return view('admin.quotations.show',['quotation'=>$quotation->load('service','customer','images')]); }
 public function update(Request $request, Quotation $quotation){ $data=$request->validate(['status'=>'required|in:new,contacted,survey_scheduled,quoted,accepted,rejected,cancelled','estimated_price'=>'nullable|numeric|min:0','appointment_at'=>'nullable|date','admin_note'=>'nullable|string|max:5000']); $quotation->update($data); return back()->with('success','บันทึกสถานะใบเสนอราคาแล้ว'); }
}
