@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
 <div><h1 class="mb-1">ใบงาน {{ $workOrder->work_order_no }}</h1><div class="text-muted">{{ $workOrder->statusText() }}</div></div>
 <div class="d-flex gap-2 flex-wrap">
  <a href="tel:{{ $workOrder->phone }}" class="btn btn-success"><i class="fa-solid fa-phone"></i> โทรหาลูกค้า</a>
  @if($workOrder->lineUrl())<a href="{{ $workOrder->lineUrl() }}" target="_blank" class="btn btn-success"><i class="fa-brands fa-line"></i> เปิด LINE</a>@endif
  @if($workOrder->mapUrl()!=='#')<a href="{{ $workOrder->mapUrl() }}" target="_blank" class="btn btn-primary"><i class="fa-solid fa-location-dot"></i> เปิดแผนที่</a>@endif
  <a href="{{ route('admin.work-orders.print',$workOrder) }}" target="_blank" class="btn btn-sj"><i class="fa-solid fa-print"></i> PDF / พิมพ์ A4</a>
 </div>
</div>
<div class="row g-3">
 <div class="col-lg-8">
  <div class="card p-4 mb-3">
   <div class="row"><div class="col-md-6"><h5>ข้อมูลลูกค้า</h5><p>ชื่อ: {{ $workOrder->customer_name }}<br>โทร: {{ $workOrder->phone }}<br>LINE: {{ $workOrder->line_id }}<br>Email: {{ $workOrder->email }}</p></div><div class="col-md-6"><h5>สถานที่หน้างาน</h5><p>ที่อยู่: {{ $workOrder->address }}<br>พิกัด: {{ $workOrder->latitude }}, {{ $workOrder->longitude }}</p>@if($workOrder->mapUrl()!=='#')<img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($workOrder->mapUrl()) }}" alt="QR Map" class="border rounded"> <div class="small text-muted mt-1">สแกนเพื่อเปิด Google Maps</div>@endif</div></div>
   <hr><h5>รายละเอียดงาน</h5><p>บริการ: {{ optional($workOrder->service)->title_th }}<br>ประเภทงาน: {{ $workOrder->job_type }}<br>จำนวนชั้น: {{ $workOrder->floors }}<br>หัวหน้าช่าง: {{ $workOrder->team_leader }}</p><p>{!! nl2br(e($workOrder->details)) !!}</p><p><b>หมายเหตุทีมงาน:</b><br>{!! nl2br(e($workOrder->admin_note)) !!}</p>
  </div>
  <div class="card p-4 mb-3"><h5>รูปใบงาน</h5><div class="row g-3">@forelse($workOrder->images as $img)<div class="col-md-3"><img src="{{ asset('storage/'.$img->image_path) }}" class="w-100 rounded shadow-sm" style="height:170px;object-fit:cover"><div class="small text-muted">{{ $img->type }}</div></div>@empty<div class="text-muted">ยังไม่มีรูปภาพ</div>@endforelse</div></div>
  <div class="card p-4 mb-3"><h5>ประวัติการเปลี่ยนสถานะ</h5>@forelse($workOrder->statusLogs as $log)<div class="border-bottom py-2"><b>{{ $log->from_status ?: '-' }}</b> → <b>{{ $log->to_status }}</b><div class="small text-muted">{{ $log->created_at?->format('d/m/Y H:i') }} โดย {{ optional($log->user)->name ?: 'System' }}</div><div>{{ $log->note }}</div></div>@empty<div class="text-muted">ยังไม่มีประวัติ</div>@endforelse</div>
 </div>
 <div class="col-lg-4">
  <div class="card p-4 mb-3"><h5>เช็คอินหน้างาน</h5><form method="post" action="{{ route('admin.work-orders.checkin',$workOrder) }}">@csrf<div class="row g-2"><div class="col-6"><input name="latitude" id="lat" class="form-control" placeholder="Latitude"></div><div class="col-6"><input name="longitude" id="lng" class="form-control" placeholder="Longitude"></div><div class="col-12"><textarea name="note" class="form-control" placeholder="หมายเหตุ"></textarea></div><div class="col-12 d-grid"><button class="btn btn-sj"><i class="fa-solid fa-location-crosshairs"></i> บันทึกเช็คอิน</button></div><div class="col-12"><button type="button" onclick="getLocation()" class="btn btn-outline-secondary w-100">ใช้ตำแหน่งปัจจุบัน</button></div></div></form>@foreach($workOrder->checkins as $c)<div class="small border-top mt-2 pt-2">{{ $c->created_at?->format('d/m/Y H:i') }}: {{ $c->latitude }}, {{ $c->longitude }}<br>{{ $c->note }}</div>@endforeach</div>
  <div class="card p-4"><h5>ลายเซ็น</h5>@foreach(['customer'=>'ลูกค้า','foreman'=>'หัวหน้าช่าง','inspector'=>'ผู้ตรวจรับ'] as $role=>$label)<div class="mb-3"><b>{{ $label }}</b>@php($field=$role.'_signature_path')@if($workOrder->$field)<img src="{{ asset('storage/'.$workOrder->$field) }}" class="d-block w-100 border rounded mt-2" style="max-height:120px;object-fit:contain">@endif<form method="post" action="{{ route('admin.work-orders.sign',$workOrder) }}" class="mt-2 signature-form">@csrf<input type="hidden" name="role" value="{{ $role }}"><input type="hidden" name="signature_data" class="signature-data"><canvas class="signature-pad border rounded w-100" height="110"></canvas><div class="d-flex gap-2 mt-2"><button class="btn btn-sm btn-sj">บันทึก</button><button type="button" class="btn btn-sm btn-outline-secondary clear-signature">ล้าง</button></div></form></div>@endforeach</div>
 </div>
</div>
@endsection
@section('scripts')
<script>
function getLocation(){ if(!navigator.geolocation){alert('เบราว์เซอร์ไม่รองรับ GPS');return;} navigator.geolocation.getCurrentPosition(p=>{document.getElementById('lat').value=p.coords.latitude.toFixed(7);document.getElementById('lng').value=p.coords.longitude.toFixed(7);}); }
document.querySelectorAll('.signature-form').forEach(form=>{const canvas=form.querySelector('canvas');const ctx=canvas.getContext('2d');let drawing=false;function pos(e){const r=canvas.getBoundingClientRect();const t=e.touches?e.touches[0]:e;return {x:t.clientX-r.left,y:t.clientY-r.top};}function start(e){drawing=true;ctx.beginPath();const p=pos(e);ctx.moveTo(p.x,p.y);e.preventDefault();}function move(e){if(!drawing)return;const p=pos(e);ctx.lineTo(p.x,p.y);ctx.stroke();e.preventDefault();}function end(){drawing=false;}['mousedown','touchstart'].forEach(ev=>canvas.addEventListener(ev,start));['mousemove','touchmove'].forEach(ev=>canvas.addEventListener(ev,move));['mouseup','mouseleave','touchend'].forEach(ev=>canvas.addEventListener(ev,end));form.querySelector('.clear-signature').onclick=()=>ctx.clearRect(0,0,canvas.width,canvas.height);form.addEventListener('submit',()=>{form.querySelector('.signature-data').value=canvas.toDataURL('image/png');});});
</script>
@endsection
