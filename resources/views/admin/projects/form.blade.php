@extends('layouts.admin')
@section('content')
<h1>{{ $project->exists?'แก้ไขผลงาน':'เพิ่มผลงาน' }}</h1>
<form method="post" enctype="multipart/form-data" action="{{ $project->exists?route('admin.projects.update',$project):route('admin.projects.store') }}" class="card p-4">@csrf @if($project->exists) @method('patch') @endif
<div class="row g-3">
 <div class="col-md-6"><label>ประเภทงาน / บริการ</label><select name="service_id" class="form-select"><option value="">ไม่ระบุ</option>@foreach($services as $s)<option value="{{ $s->id }}" @selected(old('service_id',$project->service_id)==$s->id)>{{ $s->title_th }}</option>@endforeach</select></div>
 <div class="col-md-6"><label>Slug</label><input name="slug" class="form-control" value="{{ old('slug',$project->slug) }}"></div>
 <div class="col-md-6"><label>ชื่อโครงการ TH</label><input name="title_th" class="form-control" value="{{ old('title_th',$project->title_th) }}" required></div>
 <div class="col-md-6"><label>ชื่อโครงการ EN</label><input name="title_en" class="form-control" value="{{ old('title_en',$project->title_en) }}"></div>
 <div class="col-md-6"><label>สถานที่ TH</label><input name="location_th" class="form-control" value="{{ old('location_th',$project->location_th) }}"></div>
 <div class="col-md-6"><label>สถานที่ EN</label><input name="location_en" class="form-control" value="{{ old('location_en',$project->location_en) }}"></div>
 <div class="col-md-4"><label>วันที่ดำเนินงาน</label><input name="project_date" type="date" class="form-control" value="{{ old('project_date', optional($project->project_date)->format('Y-m-d')) }}"></div>
 <div class="col-md-4"><label>สถานะ</label><select name="status" class="form-select">@foreach(['planned'=>'วางแผน','working'=>'กำลังทำ','completed'=>'เสร็จแล้ว','cancelled'=>'ยกเลิก'] as $k=>$v)<option value="{{ $k }}" @selected(old('status',$project->status ?: 'completed')==$k)>{{ $v }}</option>@endforeach</select></div>
 <div class="col-md-4"><label>งบประมาณ</label><input name="budget" type="number" step="0.01" class="form-control" value="{{ old('budget',$project->budget) }}"></div>
 <div class="col-md-6"><label>รายละเอียด TH</label><textarea name="description_th" rows="6" class="form-control">{{ old('description_th',$project->description_th) }}</textarea></div>
 <div class="col-md-6"><label>รายละเอียด EN</label><textarea name="description_en" rows="6" class="form-control">{{ old('description_en',$project->description_en) }}</textarea></div>
 <input type="hidden" name="sort_order" value="{{ old('sort_order',$project->sort_order ?? 0) }}">
 <div class="col-md-2"><label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$project->is_featured))> เด่น</label></div>
 <div class="col-md-2"><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$project->is_active ?? true))> แสดงหน้าเว็บ</label></div>

 <div class="col-12"><hr><h4>รูปภาพผลงานแบบ SJ จริง</h4><div class="admin-help">แยกรูปเป็น รูปปก / ก่อนทำ / ระหว่างทำ / หลังทำ เพื่อให้หน้าเว็บดูเป็นมืออาชีพและลูกค้าเข้าใจง่าย</div></div>
 <div class="col-md-6"><label>รูปปกโครงการ 1 รูป</label><input type="file" name="cover_image" accept="image/*" class="form-control"></div>
 <div class="col-md-6"><label>รูปก่อนทำ</label><input type="file" name="before_images[]" accept="image/*" class="form-control" multiple></div>
 <div class="col-md-6"><label>รูประหว่างทำ</label><input type="file" name="progress_images[]" accept="image/*" class="form-control" multiple></div>
 <div class="col-md-6"><label>รูปหลังทำ</label><input type="file" name="after_images[]" accept="image/*" class="form-control" multiple></div>

 @if($project->exists && $project->images->count())
 <div class="col-12"><hr><h5>รูปทั้งหมด {{ $project->images->count() }} รูป</h5><div class="row g-3">
  @foreach($project->images->groupBy('image_type') as $type=>$images)
   <div class="col-12"><h6 class="fw-bold">{{ ['cover'=>'รูปปก','before'=>'ก่อนทำ','progress'=>'ระหว่างทำ','after'=>'หลังทำ','other'=>'อื่นๆ'][$type] ?? $type }} {{ $images->count() }} รูป</h6></div>
   @foreach($images as $img)<div class="col-md-3"><div class="border rounded p-2"><img class="w-100 rounded mb-2" style="height:150px;object-fit:cover" src="{{ asset('storage/'.$img->image_path) }}"><label class="small d-block"><input type="checkbox" name="delete_images[]" value="{{ $img->id }}"> ลบรูปนี้</label><button name="set_cover_image_id" value="{{ $img->id }}" class="btn btn-sm btn-outline-warning mt-2" type="submit">⭐ ตั้งเป็นรูปปก</button></div></div>@endforeach
  @endforeach
 </div></div>
 @endif
 <div class="col-12"><button class="btn btn-sj"><i class="fa-solid fa-floppy-disk"></i> บันทึก</button><a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">กลับ</a></div>
</div></form>
@endsection
