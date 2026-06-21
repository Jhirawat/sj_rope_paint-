@extends('layouts.admin')
@section('content')
<h1>Backup / Export</h1>
<div class="row g-3">
 <div class="col-md-6"><div class="card p-4 h-100"><h5><i class="fa-solid fa-download"></i> Full JSON Backup</h5><p class="text-muted">สำรองข้อมูลหลัก: บริการ ผลงาน ใบเสนอราคา ลูกค้า ใบงาน รีวิว บทความ ตั้งค่าเว็บ</p><a class="btn btn-sj" href="{{ route('admin.backup.export-json') }}">ดาวน์โหลด JSON Backup</a></div></div>
 <div class="col-md-6"><div class="card p-4 h-100"><h5><i class="fa-solid fa-file-csv"></i> Export CSV</h5><p class="text-muted">สำหรับเปิดใน Excel หากภาษาไทยเพี้ยน ให้ import เป็น UTF-8</p><div class="d-flex gap-2 flex-wrap"><a class="btn btn-outline-primary" href="{{ route('admin.backup.export-quotations-csv') }}">ใบเสนอราคา</a><a class="btn btn-outline-primary" href="{{ route('admin.backup.export-customers-csv') }}">ลูกค้า</a><a class="btn btn-outline-primary" href="{{ route('admin.backup.export-work-orders-csv') }}">ใบงาน</a></div></div></div>
 <div class="col-12"><div class="alert alert-warning">V3 เตรียมโครง Backup รูปภาพไว้ใน Checklist แล้ว หาก deploy จริงควรทำ Zip storage/app/public เพิ่มเติมบน Server</div></div>
</div>
@endsection
