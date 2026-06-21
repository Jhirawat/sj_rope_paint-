@extends('layouts.staff')
@section('content')
<h1 class="h3 fw-bold mb-3">ค่าแรงของฉัน</h1>
<div class="big-card bg-white p-4 mb-3"><h5>ข้อมูลพนักงาน</h5><div class="row g-2"><div class="col-6"><div class="text-muted">ค่าแรงต่อวัน</div><h3>{{ number_format($employee->daily_wage) }}</h3></div><div class="col-6"><div class="text-muted">ตำแหน่ง</div><h5>{{ $employee->position_note ?: $employee->employee_type }}</h5></div></div></div>
<div class="big-card bg-white p-4"><h5>รายการรอบจ่าย</h5>@forelse($items as $it)<div class="border-bottom py-3"><div class="d-flex justify-content-between"><b>{{ $it->period?->name }}</b><span class="badge bg-secondary">{{ $it->period?->status }}</span></div><div class="row g-2 mt-1"><div class="col-6">แรง: <b>{{ number_format($it->work_units,2) }}</b></div><div class="col-6">รวม: <b>{{ number_format($it->gross_amount) }}</b></div><div class="col-6">เบิก: <b>{{ number_format($it->advance_amount) }}</b></div><div class="col-6">สุทธิ: <b class="text-success">{{ number_format($it->net_amount) }}</b></div></div></div>@empty<div class="text-muted">ยังไม่มีการสรุปค่าแรง</div>@endforelse</div>
@endsection
