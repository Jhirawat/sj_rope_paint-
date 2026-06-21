<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use Illuminate\Http\Request; use App\Models\Testimonial;
class TestimonialController extends Controller {
 public function index(){return view('admin.testimonials.index',['testimonials'=>Testimonial::latest()->paginate(20)]);} public function create(){return view('admin.testimonials.form',['testimonial'=>new Testimonial]);}
 public function store(Request $r){Testimonial::create($this->data($r)); return redirect()->route('admin.testimonials.index')->with('success','เพิ่มรีวิวแล้ว');}
 public function edit(Testimonial $testimonial){return view('admin.testimonials.form',compact('testimonial'));} public function update(Request $r, Testimonial $testimonial){$testimonial->update($this->data($r)); return back()->with('success','บันทึกรีวิวแล้ว');}
 public function destroy(Testimonial $testimonial){$testimonial->delete(); return back()->with('success','ลบรีวิวแล้ว');}
 private function data(Request $r){$d=$r->validate(['customer_name'=>'required|max:255','company'=>'nullable|max:255','message_th'=>'required','message_en'=>'nullable','rating'=>'required|integer|min:1|max:5','is_active'=>'nullable|boolean']); $d['is_active']=$r->boolean('is_active'); return $d;}
}
