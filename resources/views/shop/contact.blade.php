@extends('layouts.app')
@php
use App\Models\SiteSetting;
$en = app()->getLocale()==='en';
$phones = json_decode(SiteSetting::get('contact_phones','[]'), true) ?: [['label'=>$en?'Main phone':'เบอร์หลัก','number'=>SiteSetting::get('phone','081-353-7779')]];
$socials = json_decode(SiteSetting::get('social_links','[]'), true) ?: [];
$maps = SiteSetting::get('google_maps_link','#');
$lat = SiteSetting::get('latitude',''); $lng = SiteSetting::get('longitude','');
$address = $en ? SiteSetting::get('address_en') : SiteSetting::get('address_th');
$areas = $en ? SiteSetting::get('service_area_en') : SiteSetting::get('service_area_th');
$contactSocialIcon = function ($name) {
    $n = strtolower($name ?? '');
    if (str_contains($n, 'line')) return 'fa-brands fa-line';
    if (str_contains($n, 'facebook') || str_contains($n, 'messenger')) return 'fa-brands fa-facebook-f';
    if (str_contains($n, 'tiktok')) return 'fa-brands fa-tiktok';
    if (str_contains($n, 'youtube')) return 'fa-brands fa-youtube';
    if (str_contains($n, 'map')) return 'fa-solid fa-map-location-dot';
    return 'fa-solid fa-link';
};

@endphp
@section('content')
<section class="section bg-light"><div class="container"><div class="text-center mb-5"><span class="badge badge-safety rounded-pill px-3 py-2"><i class="fa-solid fa-headset"></i> {{ $en?'Contact SJ Team':'ติดต่อทีมงาน SJ' }}</span><h1 class="fw-bold mt-3">{{ $en?'Contact Us':'ติดต่อเรา' }}</h1><p class="text-muted">{{ $en?'Send your worksite photos, map link or call us for a quick estimate.':'ส่งรูปหน้างาน ลิงก์แผนที่ หรือโทรสอบถามเพื่อประเมินเบื้องต้น' }}</p></div><div class="row g-4">
<div class="col-lg-4"><div class="soft-card p-4 h-100"><div class="icon-box mb-3"><i class="fa-solid fa-phone-volume"></i></div><h4>{{ $en?'Contact Information':'ข้อมูลติดต่อ' }}</h4>@foreach($phones as $p)@php($personName = $en ? ($p['person_en'] ?? $p['label_en'] ?? '') : ($p['person'] ?? $p['label'] ?? ''))<div class="d-flex gap-2 align-items-start mb-2"><i class="fa-solid fa-phone text-warning mt-1"></i><div><div><span class="fw-semibold">{{ $en ? ($p['label_en'] ?? $p['label'] ?? 'Phone') : ($p['label'] ?? 'โทร') }}</span></div><a class="text-dark text-decoration-none" href="tel:{{ preg_replace('/[^0-9+]/','',$p['number'] ?? '') }}">{{ $p['number'] ?? '' }}</a>@if($personName)<span class="text-muted small ms-1">{{ $personName }}</span>@endif</div></div>@endforeach<div class="d-flex gap-2 align-items-center mb-2"><i class="fa-brands fa-line text-success"></i><span>{{ SiteSetting::get('line_id','@sjrope') }}</span></div><div class="d-flex gap-2 align-items-center mb-2"><i class="fa-solid fa-envelope text-primary"></i><span>{{ SiteSetting::get('email','contact@sjrope.test') }}</span></div><hr><div class='small text-muted mb-2'><i class='fa-solid fa-location-dot text-warning'></i> {{ $address }}</div><div class='small text-muted mb-3'><i class='fa-solid fa-map-pin text-warning'></i> {{ $en?'Service Areas':'พื้นที่บริการ' }}: {{ $areas }}</div><h6>{{ $en?'Social channels':'ช่องทางออนไลน์' }}</h6><div class="social-icons">@foreach($socials as $soc)<a href="{{ str_starts_with(($soc['url'] ?? ''),'http') ? $soc['url'] : '#' }}" target="_blank" title="{{ $soc['name'] ?? 'Social' }}"><i class="{{ $contactSocialIcon($soc['name'] ?? '') }}"></i></a>@endforeach</div></div></div>
<div class="col-lg-4"><div class="soft-card p-4 h-100"><div class="icon-box mb-3"><i class="fa-solid fa-map-location-dot"></i></div><h4>{{ $en?'Worksite / Office Map':'แผนที่หน้างาน / สำนักงาน' }}</h4><p class="text-muted">{{ $en?'Open Google Maps or send your worksite link in the quotation form.':'เปิด Google Maps หรือส่งลิงก์หน้างานในแบบฟอร์มใบเสนอราคา' }}</p><iframe class="map-preview mb-3" loading="lazy" src="https://www.google.com/maps?q=SJ%20ทาสีโรยตัว%20ชะอำ%20เพชรบุรี&output=embed"></iframe><a class="btn btn-sj w-100" href="{{ $maps }}" target="_blank"><i class="fa-solid fa-location-arrow"></i>{{ $en?'Open in Google Maps':'เปิดใน Google Maps' }}</a></div></div>
<div class="col-lg-4"><div class="soft-card p-4 h-100"><div class="icon-box mb-3"><i class="fa-solid fa-message"></i></div><h4>{{ $en?'Fast Estimate':'ประเมินงานด่วน' }}</h4><p class="text-muted">{{ $en?'For accurate pricing, send your worksite details, map link and photos through the quotation form.':'เพื่อประเมินราคาแม่นยำ กรุณาส่งรายละเอียดหน้างาน ลิงก์แผนที่ และรูปภาพผ่านฟอร์มใบเสนอราคา' }}</p><div class="d-grid gap-2"><a class="btn btn-sj" href="{{ route('quote.form') }}"><i class="fa-solid fa-clipboard-list"></i>{{ $en?'Request a Quotation':'ขอใบเสนอราคา' }}</a><a class="btn btn-success" href="tel:{{ preg_replace('/[^0-9+]/','',$phones[0]['number'] ?? '') }}"><i class="fa-solid fa-phone"></i>{{ $en?'Call Now':'โทรเลย' }}</a><a class="btn btn-info text-white" href="{{ str_starts_with(SiteSetting::get('line_url',''),'http') ? SiteSetting::get('line_url') : 'https://line.me/R/ti/p/'.SiteSetting::get('line_id','@sjrope') }}" target="_blank"><i class="fa-brands fa-line"></i>LINE</a></div></div></div>
</div></div></section>
@endsection
