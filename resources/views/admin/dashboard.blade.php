@extends('layouts.admin')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2"><div><h1 class="h3 mb-1">Dashboard เจ้าของ / Admin</h1><div class="text-muted">เปิดหน้าเดียวเห็นเงินเบิก เข้างาน สาย ขาด และแรงรวมวันนี้</div></div><a href="{{ route('admin.owner-reports.index') }}" class="btn btn-sj"><i class="fa-solid fa-ranking-star"></i> ดู KPI เดือนนี้</a></div>
<div class="row g-3 mb-4">
 @foreach([
  ['ใบเสนอราคาใหม่',$uncontactedQuotes,'fa-file-signature','primary'],
  ['กำลังติดตาม',$followUpQuotes,'fa-phone-volume','info'],
  ['งานเปิดอยู่',$openWorkOrders,'fa-clipboard-list','primary'],
  ['งานกำลังทำ',$workingCount,'fa-person-digging','warning'],
  ['งานเสร็จแล้ว',$completedWorkOrders,'fa-circle-check','success'],
  ['ผลงานที่แสดง',$activeProjectCount,'fa-images','success'],
  ['ผู้เข้าชมเว็บ',$visitorCount,'fa-chart-simple','secondary'],
  ['เงินเบิกรออนุมัติ',$pendingAdvances->count(),'fa-hand-holding-dollar','danger'],
 ] as $c)
 <div class="col-6 col-lg-3"><div class="kpi-card"><div class="d-flex align-items-center gap-3"><div class="icon-box bg-{{ $c[3] }}-subtle text-{{ $c[3] }}"><i class="fa-solid {{ $c[2] }} fs-4"></i></div><div><div class="text-muted small">{{ $c[0] }}</div><div class="h3 mb-0">{{ $c[1] }}</div></div></div></div></div>
 @endforeach
</div>
<div class="row g-4 mb-4">
 <div class="col-lg-6"><div class="card p-3 h-100"><h5><i class="fa-solid fa-file-invoice text-primary"></i> ใบเสนอราคาล่าสุด</h5>@forelse($latestQuotes as $q)<div class="d-flex justify-content-between border-bottom py-2"><div><b>{{ $q->quotation_no }}</b><br><span class="text-muted small">{{ $q->name }} • {{ $q->phone }}</span></div><a href="{{ route('admin.quotations.show',$q) }}" class="btn btn-sm btn-outline-primary">ดู</a></div>@empty<div class="text-muted">ยังไม่มีใบเสนอราคา</div>@endforelse</div></div>
 <div class="col-lg-6"><div class="card p-3 h-100"><h5><i class="fa-solid fa-briefcase text-warning"></i> งานที่ต้องติดตาม</h5>@forelse($latestWorkOrders as $w)<div class="d-flex justify-content-between border-bottom py-2"><div><b>{{ $w->work_order_no }}</b><br><span class="text-muted small">{{ $w->customer_name }} • {{ $w->status }}</span></div><a href="{{ route('admin.work-orders.show',$w) }}" class="btn btn-sm btn-outline-primary">ดู</a></div>@empty<div class="text-muted">ยังไม่มีใบงาน</div>@endforelse</div></div>
</div>
<div class="row g-3 mb-4">
 @foreach([
  ['พนักงานทั้งหมด',$activeEmployeeCount,'fa-users','primary'],['เข้างานวันนี้',$todayAttendanceCount,'fa-user-check','success'],['สายวันนี้',$todayLateCount,'fa-clock','warning'],['ขาดวันนี้',$todayAbsentCount,'fa-user-xmark','danger'],['แรงรวมวันนี้',$todayWorkUnits,'fa-gauge-high','info'],['แจ้งเตือนยังไม่อ่าน',$unreadNotifications,'fa-bell','warning'],
 ] as $c)
 <div class="col-6 col-lg-2"><div class="kpi-card"><div class="d-flex align-items-center gap-3"><div class="icon-box bg-{{ $c[3] }}-subtle text-{{ $c[3] }}"><i class="fa-solid {{ $c[2] }}"></i></div><div><div class="text-muted small">{{ $c[0] }}</div><div class="h4 mb-0">{{ $c[1] }}</div></div></div></div></div>
 @endforeach
</div>
@if($specialAdvances->count())<div class="alert alert-danger d-flex justify-content-between align-items-center"><div><b>มีคำขอเบิกเกิน 1,000 บาท</b> จำนวน {{ $specialAdvances->count() }} รายการ ต้องให้เจ้าของตรวจสอบ</div><a href="{{ route('admin.advances.index') }}" class="btn btn-sm btn-light">ไปที่เงินเบิก</a></div>@endif
<div class="row g-4">
 <div class="col-lg-6"><div class="card p-3"><h5><i class="fa-solid fa-camera text-primary"></i> รูปเข้างานล่าสุดวันนี้</h5><div class="row g-2">@forelse($todayAttendance as $r)<div class="col-md-6"><div class="d-flex gap-2 border rounded-4 p-2 align-items-center">@if($r->check_in_photo)<img class="avatar-sm" src="{{ asset('storage/'.$r->check_in_photo) }}">@else<div class="avatar-sm d-grid place-items-center bg-light text-muted"><i class="fa-solid fa-user"></i></div>@endif<div><b>{{ $r->employee?->name }}</b><br><span class="small text-muted">{{ optional($r->check_in_at)->format('H:i') }}</span> @if($r->is_late)<span class="badge text-bg-danger">สาย {{ $r->late_minutes }} นาที</span>@else<span class="badge text-bg-success">ปกติ</span>@endif</div></div></div>@empty<div class="text-muted">ยังไม่มีการเข้างานวันนี้</div>@endforelse</div></div></div>
 <div class="col-lg-6"><div class="card p-3"><h5><i class="fa-solid fa-hand-holding-dollar text-warning"></i> รายการเบิกเงินรออนุมัติ</h5>@forelse($pendingAdvances as $a)<div class="d-flex justify-content-between align-items-center border-bottom py-2"><div><b>{{ $a->employee?->name }}</b> เบิก {{ number_format($a->amount) }} บาท<br><span class="small text-muted">{{ optional($a->request_date)->format('d/m/Y') }} {{ $a->reason }}</span>@if($a->is_special_request)<span class="badge text-bg-danger ms-1">พิเศษ</span>@endif</div><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.advances.index') }}">ตรวจ</a></div>@empty<div class="text-muted">ไม่มีรายการรออนุมัติ</div>@endforelse</div></div>
 <div class="col-lg-6"><div class="card p-3"><h5>🏆 Top ขยัน เดือนนี้</h5>@foreach($topHardWorkers as $i=>$r)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $i+1 }}. {{ $r->employee->name }}</span><b>{{ $r->work_units }} แรง</b></div>@endforeach</div></div>
 <div class="col-lg-6"><div class="card p-3"><h5>⏰ Top สาย เดือนนี้</h5>@foreach($topLate as $i=>$r)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $i+1 }}. {{ $r->employee->name }}</span><b class="text-danger">{{ $r->late_count }} ครั้ง</b></div>@endforeach</div></div>
 <div class="col-lg-6"><div class="card p-3"><h5>🚫 Top ขาดงาน เดือนนี้</h5>@foreach($topAbsent as $i=>$r)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $i+1 }}. {{ $r->employee->name }}</span><b class="text-danger">{{ $r->absent_count }} ครั้ง</b></div>@endforeach</div></div>
 <div class="col-lg-6"><div class="card p-3"><h5>💰 Top เบิกเงิน เดือนนี้</h5>@foreach($topAdvance as $i=>$r)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $i+1 }}. {{ $r->employee->name }}</span><b>{{ number_format($r->advance_total) }} บาท</b></div>@endforeach</div></div>
 <div class="col-lg-12"><div class="card p-3"><h5>🌟 พนักงานดีเด่น</h5><div class="row">@forelse($hallOfFame as $r)<div class="col-md-4"><div class="border rounded-4 p-3 mb-2"><b>{{ $r->employee->name }}</b><div class="small text-muted">{{ $r->work_units }} แรง / ไม่สาย / ไม่ขาด</div></div></div>@empty<div class="text-muted">ยังไม่มีข้อมูลพนักงานดีเด่นในเดือนนี้</div>@endforelse</div></div></div>
</div>
@endsection
