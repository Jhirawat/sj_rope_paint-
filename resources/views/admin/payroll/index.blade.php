@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><h1 class="h3 mb-0">รอบจ่ายค่าแรง</h1><form><input type="month" name="month" value="{{ $month }}" class="form-control" onchange="this.form.submit()"></form></div>
<div class="row g-3">@foreach($periods as $p)<div class="col-md-6"><div class="card p-4"><h5>{{ $p->name }}</h5><p class="text-muted">{{ $p->start_date->format('d/m/Y') }} - {{ $p->end_date->format('d/m/Y') }}</p>@php($paid = $p->status==='paid' || $p->status==='confirmed')<span class="badge mb-3 {{ $paid ? 'bg-success' : 'bg-danger' }}">{{ $paid ? 'จ่ายแล้ว / paid' : 'ยังไม่จ่าย / not paid' }}</span><div><a href="{{ route('admin.payroll.show',$p) }}" class="btn btn-sj">ดูสรุปค่าแรง</a></div></div></div>@endforeach</div>
@endsection
