@extends('layouts.app')
@section('title', app()->getLocale()==='en' ? 'SJ Rope Access Painting | High-rise Painting and Facade Repair' : 'SJ ทาสีโรยตัว | รับทาสีอาคารสูงและงานโรยตัว')
@section('content')
@php
    $en = app()->getLocale()==='en';
    $mainLogo = asset('images/sj-v9/sj-logo-main.png').'?v=100';
@endphp
<style>
    .hero-v10{background:#fff;position:relative;overflow:hidden;color:#082B5B;}
    .hero-v10 .hero-bg{position:absolute;inset:0;background:linear-gradient(90deg,rgba(255,255,255,.97) 0%,rgba(255,255,255,.88) 43%,rgba(255,255,255,.18) 64%,rgba(255,255,255,.02) 100%),url('https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?q=80&w=1800&auto=format&fit=crop') center/cover no-repeat;}
    .hero-v10 .container{position:relative;z-index:2;}
    .hero-v10 h1{font-size:clamp(2.4rem,5vw,4.6rem);line-height:1.08;color:#082B5B;letter-spacing:-.03em;}
    .hero-v10 .lead{color:#173a67;font-weight:600;}
    .hero-info-card{background:#fff;border:1px solid #e6edf5;border-radius:22px;box-shadow:0 24px 70px rgba(8,43,91,.16);padding:28px;max-width:390px;margin-left:auto;}
    .hero-info-card .main-logo{display:block;width:100%;max-height:190px;object-fit:contain;margin:0 auto 18px;}
    .hero-info-line{display:flex;gap:10px;align-items:flex-start;margin-bottom:10px;color:#11243c;font-weight:700;}
    .hero-info-line i{color:#F4B400;margin-top:4px;min-width:18px;}
    .hero-address{font-size:.94rem;font-weight:600;color:#1f334e;}
    .feature-row{background:linear-gradient(90deg,#062552,#0d4c99);color:#fff;}
    .feature-row .feature-box{display:flex;align-items:center;gap:14px;padding:24px 10px;justify-content:center;}
    .feature-row .icon{font-size:34px;color:#fff;opacity:.94;}
    .feature-row .title{font-weight:800;font-size:1.05rem;}
    .feature-row .desc{font-size:.92rem;color:rgba(255,255,255,.82);}
    .btn-hero-outline{border:2px solid #082B5B;color:#082B5B;background:#fff;font-weight:800;}
    .btn-hero-outline:hover{background:#082B5B;color:#fff;}
    .mock-section{background:#F8FAFC;}
    @media(max-width:991px){.hero-v10 .hero-bg{background:linear-gradient(180deg,rgba(255,255,255,.98),rgba(255,255,255,.88)),url('https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat}.hero-info-card{margin:25px 0 0;max-width:none}.feature-row .feature-box{justify-content:flex-start}.hero-v10 h1{font-size:2.5rem}}
</style>

<section class="hero-v10">
    <div class="hero-bg"></div>
    <div class="container py-5 py-lg-6" style="min-height:620px;display:flex;align-items:center;">
        <div class="row align-items-center g-5 w-100">
            <div class="col-lg-7">
                <span class="badge badge-safety rounded-pill px-3 py-2 mb-3"><i class="fa-solid fa-shield-halved"></i> {{ $en?'Safety-first rope access team':'ทีมโรยตัวเน้นความปลอดภัย' }}</span>
                <h1 class="fw-black mb-3">{{ $en?'Rope Access Painting, Safety and Quality First':'งานทาสีโรยตัว มาตรฐานสูง ปลอดภัย ใส่ใจคุณภาพ' }}</h1>
                <p class="lead mb-4">{{ $en?'Quality team with real on-site experience. Clear site survey, quotation and professional handover.':'ทีมงานคุณภาพ ผ่านประสบการณ์หน้างานจริง พร้อมสำรวจหน้างานและประเมินราคาอย่างชัดเจน' }}</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a class="btn btn-sj btn-lg px-4" href="{{ route('quote.form') }}"><i class="fa-solid fa-file-signature"></i>{{ $en?'Request Quotation':'ขอใบเสนอราคา' }}</a>
                    <a class="btn btn-hero-outline btn-lg px-4" href="tel:0813537779"><i class="fa-solid fa-phone"></i>{{ $en?'Call Now':'โทรเลย' }}</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-info-card">
                    <img src="{{ $mainLogo }}" class="main-logo" alt="SJ Rope Painting">
                    <div class="hero-info-line"><i class="fa-solid fa-phone"></i><div>081-353-7779 <span class="fw-normal">คุณอิ้ด</span></div></div>
                    <div class="hero-info-line"><i class="fa-solid fa-phone"></i><div>092-284-5996 <span class="fw-normal">คุณก้อย</span></div></div>
                    <div class="hero-info-line"><i class="fa-solid fa-location-dot"></i><div class="hero-address">{{ $en?'1309/15 (Sai Tai), Chonprathan Road, Cha-am, Phetchaburi 76120':'1309/15 (ทรายใต้) ถ.ชลประทาน ต.ชะอำ อ.ชะอำ จ.เพชรบุรี 76120' }}</div></div>
                    <div class="mt-3 pt-2 border-top">
                        <h5 class="fw-bold mb-2"><i class="fa-solid fa-map-pin text-warning"></i> {{ $en?'Service Areas':'พื้นที่บริการ' }}</h5>
                        <ul class="mb-0 ps-4">
                            <li>{{ $en?'Hua Hin – Cha-am (Primary Area)':'หัวหิน-ชะอำ (พื้นที่หลัก)' }}</li>
                            <li>{{ $en?'Bangkok':'กรุงเทพมหานคร' }}</li>
                            <li>{{ $en?'Chonburi':'ชลบุรี' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="feature-row">
    <div class="container">
        <div class="row g-0 text-center text-lg-start">
            <div class="col-md-3"><div class="feature-box"><i class="icon fa-solid fa-medal"></i><div><div class="title">{{ $en?'5+ Years':'ประสบการณ์มากกว่า 5 ปี' }}</div><div class="desc">{{ $en?'On-site experience':'ประสบการณ์หน้างาน' }}</div></div></div></div>
            <div class="col-md-3"><div class="feature-box"><i class="icon fa-solid fa-users"></i><div><div class="title">{{ $en?'Quality Team':'ทีมงานคุณภาพ' }}</div><div class="desc">{{ $en?'Real project experience':'ผ่านประสบการณ์หน้างานจริง' }}</div></div></div></div>
            <div class="col-md-3"><div class="feature-box"><i class="icon fa-solid fa-shield-halved"></i><div><div class="title">{{ $en?'Safety Standard':'มาตรฐานความปลอดภัย' }}</div><div class="desc">PPE / Harness / Work Plan</div></div></div></div>
            <div class="col-md-3"><div class="feature-box"><i class="icon fa-solid fa-map-location-dot"></i><div><div class="title">{{ $en?'Primary Area':'พื้นที่หลัก' }}</div><div class="desc">{{ $en?'Hua Hin – Cha-am':'หัวหิน-ชะอำ' }}</div></div></div></div>
        </div>
    </div>
</section>

<section class="section mock-section"><div class="container"><div class="text-center mb-5"><h2 class="fw-bold">{{ $en?'Our Services':'บริการของเรา' }}</h2><p class="text-muted">{{ $en?'High-rise painting, rope access repair, waterproofing and facade maintenance.':'ทาสีอาคารสูง งานโรยตัว ซ่อมรอยร้าว กันซึม ล้างกระจก และซ่อมบำรุงภายนอกอาคาร' }}</p></div><div class="row g-4">@foreach($services as $service)<div class="col-md-6 col-lg-4"><div class="card card-sj h-100">@if($service->image)<img src="{{ asset('storage/'.$service->image) }}" class="service-cover" alt="{{ $service->title() }}">@endif<div class="p-4"><div class="icon-box mb-3"><i class="{{ $service->icon ?: 'fa-solid fa-hard-hat' }}"></i></div><h5 class="fw-bold">{{ $service->title() }}</h5><p class="text-muted">{{ $service->excerpt() }}</p><a href="{{ route('services.show',$service) }}" class="stretched-link text-decoration-none">{{ $en?'View Details':'ดูรายละเอียด' }}</a></div></div></div>@endforeach</div></div></section>

<section class="section">
 <div class="container">
  <div class="row align-items-center g-4">
   <div class="col-lg-4"><h2 class="fw-bold">{{ $en?'Before / After':'เปรียบเทียบก่อนทำ / หลังทำ' }}</h2><p class="text-muted">{{ $en?'Real work photos help customers see the difference clearly.':'รูปงานจริงช่วยให้ลูกค้าเห็นผลลัพธ์ชัดเจนและตัดสินใจง่ายขึ้น' }}</p></div>
   <div class="col-lg-8">
    @php $before = $featuredProject?->images?->where('image_type','before')->first(); $after = $featuredProject?->images?->where('image_type','after')->first(); @endphp
    <div class="card card-sj p-3">
     <div class="before-after">
      <div><span class="badge text-bg-secondary mb-2">{{ $en?'Before':'ก่อนทำ' }}</span>@if($before)<img class="ba-img" src="{{ asset('storage/'.$before->image_path) }}">@else<div class="project-ph">BEFORE</div>@endif</div>
      <div><span class="badge text-bg-warning mb-2">{{ $en?'After':'หลังทำ' }}</span>@if($after)<img class="ba-img" src="{{ asset('storage/'.$after->image_path) }}">@else<div class="project-ph">AFTER</div>@endif</div>
     </div>
    </div>
   </div>
  </div>
 </div>
</section>

<section class="section bg-light"><div class="container"><div class="d-flex justify-content-between align-items-end mb-4"><div><h2 class="fw-bold">{{ $en?'Latest Projects':'ผลงานล่าสุด' }}</h2><p class="text-muted">{{ $en?'Show real project albums, before-after photos and job details.':'เหมาะกับการโชว์อัลบั้ม Before / After และโปรเจกต์จริง' }}</p></div><a href="{{ route('projects') }}" class="btn btn-outline-primary">{{ $en?'View All':'ดูทั้งหมด' }}</a></div><div class="row g-4">@foreach($projects as $project)<div class="col-md-6 col-lg-4"><div class="card card-sj h-100">@php $cover=$project->images->first(); @endphp @if($cover)<img src="{{ asset('storage/'.$cover->image_path) }}" class="project-cover" alt="{{ $project->title() }}">@else<div class="project-ph">SJ PROJECT</div>@endif<div class="p-3 d-flex flex-column h-100"><span class="badge text-bg-primary align-self-start mb-2">{{ optional($project->service)->title() }}</span><h5>{{ $project->title() }}</h5><p class="text-muted"><i class="fa-solid fa-location-dot"></i> {{ $project->location() }}</p><a href="{{ route('projects.show',$project) }}" class="btn btn-sm btn-sj mt-auto">{{ $en?'Details':'รายละเอียด' }}</a></div></div></div>@endforeach</div></div></section>

<section class="section"><div class="container"><div class="row g-4 align-items-center"><div class="col-lg-4"><h2 class="fw-bold">{{ $en?'How We Work':'ขั้นตอนการทำงาน' }}</h2><p class="text-muted">{{ $en?'A clear workflow helps customers understand the process before requesting a quote.':'อธิบายขั้นตอนให้ลูกค้าเข้าใจง่ายก่อนขอใบเสนอราคา' }}</p></div><div class="col-lg-8"><div class="row g-3">@foreach($en ? ['Send job photos / location','Admin contacts back','Site survey and quotation','Start work safely','Handover with photos'] : ['ส่งรูปหน้างาน / โลเคชั่น','เจ้าหน้าที่ติดต่อกลับ','สำรวจหน้างานและเสนอราคา','เริ่มงานอย่างปลอดภัย','ส่งมอบงานพร้อมรูปภาพ'] as $i=>$step)<div class="col-md-6"><div class="card card-sj p-4 h-100"><div class="d-flex gap-3"><div class="step-num">{{ $i+1 }}</div><div><b>{{ $step }}</b></div></div></div></div>@endforeach</div></div></div></div></section>

<section class="section bg-light"><div class="container"><div class="row g-4"><div class="col-lg-4"><h2 class="fw-bold">{{ $en?'Safety Standards':'มาตรฐานความปลอดภัย' }}</h2><p class="text-muted">{{ $en?'Rope access work must clearly communicate safety, equipment and handover process.':'งานโรยตัวต้องชัดเจนเรื่องความปลอดภัย อุปกรณ์ และการส่งมอบงาน' }}</p></div><div class="col-lg-8"><div class="row g-3">@foreach($en ? [['PPE / Harness','Full safety equipment before work'],['Site Survey','Check area before pricing'],['Work Plan','Clear team and site operation plan'],['Handover','Final inspection and photo evidence']] : [['PPE / Harness','อุปกรณ์นิรภัยครบก่อนเริ่มงาน'],['สำรวจหน้างาน','สำรวจหน้างานก่อนประเมินราคา'],['แผนการทำงาน','กำหนดแผนงานและพื้นที่ปฏิบัติงาน'],['ส่งมอบงาน','ส่งมอบงานพร้อมตรวจเช็กความเรียบร้อย']] as $item)<div class="col-md-6"><div class="card card-sj p-4 h-100"><b>{{ $item[0] }}</b><p class="mb-0 text-muted">{{ $item[1] }}</p></div></div>@endforeach</div></div></div></div></section>
@endsection
