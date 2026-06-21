@extends('layouts.staff')
@section('content')
<h1 class="h3 fw-bold mb-3">ใบงาน</h1><div class="small text-muted mb-3">แสดงใบงานล่าสุด ใช้ดูที่อยู่ เบอร์ลูกค้า แผนที่ และสถานะงาน</div>
@foreach($orders as $w)<div class="big-card bg-white p-4 mb-3"><div class="d-flex justify-content-between"><b>{{ $w->work_order_no }}</b><span class="badge bg-primary">{{ $w->statusText() }}</span></div><h5 class="mt-2">{{ $w->customer_name }}</h5><div class="text-muted">{{ $w->address }}</div><div class="d-flex gap-2 flex-wrap mt-3"><a class="btn btn-success" href="tel:{{ preg_replace('/[^0-9+]/','',$w->phone) }}"><i class="fa-solid fa-phone"></i> โทร</a>@if($w->line_id)<a class="btn btn-info text-white" href="https://line.me/R/ti/p/{{ $w->line_id }}"><i class="fa-brands fa-line"></i> LINE</a>@endif@if($w->map_link)<a class="btn btn-primary" target="_blank" href="{{ $w->map_link }}"><i class="fa-solid fa-map-location-dot"></i> แผนที่</a>@endif</div></div>@endforeach{{ $orders->links() }}
@endsection
