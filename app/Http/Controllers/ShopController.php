<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\{Service,Project,Quotation,Customer,QuotationImage,Testimonial,Article,SiteSetting,VisitorLog};

class ShopController extends Controller
{
    private function logVisit(Request $request): void
    {
        try {
            $ua = strtolower($request->userAgent() ?? '');
            $device = str_contains($ua,'mobile') ? 'mobile' : (str_contains($ua,'tablet') ? 'tablet' : 'desktop');
            VisitorLog::create(['path'=>$request->path(),'locale'=>app()->getLocale(),'device_type'=>$device,'ip_hash'=>hash('sha256',$request->ip().'sj'),'user_agent'=>$request->userAgent()]);
        } catch (\Throwable $e) {}
    }

    public function home(Request $request)
    {
        $this->logVisit($request);
        return view('shop.home',[
            'services'=>Service::where('is_active',1)->orderBy('sort_order')->get(),
            'projects'=>Project::with('service','images')->where('is_active',1)->orderBy('sort_order')->latest()->take(6)->get(),
            'featuredProject'=>Project::with('images','service')->where('is_active',1)->where('is_featured',1)->latest()->first(),
            'testimonials'=>Testimonial::where('is_active',1)->latest()->take(6)->get(),
            'articles'=>Article::where('status','published')->latest('published_at')->take(3)->get()
        ]);
    }
    public function services(Request $request){ $this->logVisit($request); return view('shop.services',['services'=>Service::where('is_active',1)->orderBy('sort_order')->get()]); }
    public function service(Request $request, Service $service){ $this->logVisit($request); abort_unless($service->is_active,404); return view('shop.service-show',['service'=>$service,'projects'=>$service->projects()->with('images')->where('is_active',1)->latest()->take(6)->get()]); }
    public function projects(Request $request){ $this->logVisit($request); return view('shop.projects',['projects'=>Project::with('service','images')->where('is_active',1)->orderBy('sort_order')->latest()->paginate(12)]); }
    public function project(Request $request, Project $project){ $this->logVisit($request); abort_unless($project->is_active,404); return view('shop.project-show',['project'=>$project->load('service','images')]); }
    public function articles(Request $request){ $this->logVisit($request); return view('shop.articles',['articles'=>Article::where('status','published')->latest('published_at')->paginate(9)]); }
    public function article(Request $request, Article $article){ $this->logVisit($request); abort_unless($article->status==='published',404); return view('shop.article-show',compact('article')); }
    public function quoteForm(Request $request){ $this->logVisit($request); return view('shop.quote',['services'=>Service::where('is_active',1)->orderBy('sort_order')->get()]); }
    public function quoteStore(Request $request)
    {
        $data=$request->validate([
            'name'=>'required|string|max:255',
            'phone'=>['required','string','max:30','regex:/^[0-9+\- ]{8,20}$/'],
            'line_id'=>'nullable|string|max:100',
            'email'=>'nullable|email|max:255',
            'service_id'=>'required|exists:services,id',
            'building_type'=>'required|in:Condo,Hotel,Office,Factory,Shopping Mall,House,Other',
            'floors'=>'required|integer|min:1|max:200',
            'budget_range'=>'nullable|string|max:100',
            'location'=>'nullable|string|max:255',
            'province'=>'required|string|max:100',
            'district'=>'required|string|max:100',
            'subdistrict'=>'required|string|max:100',
            'postcode'=>['required','string','max:20','regex:/^[0-9]{5}$/'],
            'address'=>'required|string|max:1000',
            'map_link'=>'nullable|url|max:500',
            'latitude'=>'nullable|numeric|between:-90,90',
            'longitude'=>'nullable|numeric|between:-180,180',
            'details_short'=>'nullable|string|max:255',
            'details'=>'nullable|string|max:5000',
            'images'=>'nullable|array|max:10',
            'images.*'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
        ],[
            'phone.regex'=>app()->getLocale()==='en'?'Please enter a valid phone number.':'กรุณากรอกเบอร์โทรให้ถูกต้อง',
            'postcode.regex'=>app()->getLocale()==='en'?'Please select a valid postcode.':'กรุณาเลือกรหัสไปรษณีย์ให้ถูกต้อง',
            'map_link.url'=>app()->getLocale()==='en'?'Please enter a valid Google Maps link.':'กรุณาใส่ลิงก์ Google Maps ให้ถูกต้อง'
        ]);
        $customer=Customer::firstOrCreate(['phone'=>$data['phone']], ['name'=>$data['name'],'line_id'=>$data['line_id']??null,'email'=>$data['email']??null,'address'=>trim(($data['address'] ?? '').' '.($data['subdistrict'] ?? '').' '.($data['district'] ?? '').' '.($data['province'] ?? '').' '.($data['postcode'] ?? '')) ?: ($data['location'] ?? null)]);
        $q=Quotation::create(array_merge($data,['customer_id'=>$customer->id,'quotation_no'=>'SJQ-'.now()->format('Ymd').'-'.str_pad((string)(Quotation::count()+1),4,'0',STR_PAD_LEFT),'status'=>'new']));
        $seenImages=[];
        foreach($request->file('images',[]) as $file){
            $fingerprint=$file->getClientOriginalName().'-'.$file->getSize();
            if(isset($seenImages[$fingerprint])){ continue; }
            $seenImages[$fingerprint]=true;
            QuotationImage::create(['quotation_id'=>$q->id,'image_path'=>$file->store('quotations','public'),'original_name'=>$file->getClientOriginalName()]);
        }
        return redirect()->route('quote.track.form')->with('success', (app()->getLocale()==='en' ? 'Request submitted. Tracking code: ' : 'ส่งคำขอแล้ว รหัสติดตาม: ').$q->quotation_no);
    }
    public function trackForm(){ return view('shop.track'); }
    public function track(Request $request)
    {
        $data=$request->validate(['quotation_no'=>'required|string|max:50','phone'=>'required|string|max:30']);
        $quotation=Quotation::with('service')->where('quotation_no',$data['quotation_no'])->where('phone',$data['phone'])->first();
        return view('shop.track',['quotation'=>$quotation,'searched'=>true]);
    }
    public function contact(Request $request){ $this->logVisit($request); return view('shop.contact'); }
    public function language(string $locale){ abort_unless(in_array($locale,['th','en'],true),404); session(['locale'=>$locale]); return back(); }
}
