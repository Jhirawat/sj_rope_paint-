@extends('layouts.admin')
@section('content')
<h1 class="h3 mb-4">Audit Log</h1>
<div class="card p-3"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>เวลา</th><th>ผู้ใช้</th><th>Module</th><th>Action</th><th>รายละเอียด</th></tr></thead><tbody>@foreach($logs as $l)<tr><td>{{ $l->created_at->format('d/m/Y H:i') }}</td><td>{{ $l->user?->name ?: '-' }}</td><td>{{ $l->module }}</td><td><span class="badge text-bg-secondary">{{ $l->action }}</span></td><td>{{ $l->note }}</td></tr>@endforeach</tbody></table></div>{{ $logs->links() }}</div>
@endsection
