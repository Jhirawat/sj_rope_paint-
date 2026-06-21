@extends('layouts.app')
@section('title',$project->title())
@section('content')
<section class="section"><div class="container">
 <div class="row g-4 align-items-start">
  <div class="col-lg-8"><h1 class="fw-bold">{{ $project->title() }}</h1><p class="text-muted"><i class="fa-solid fa-briefcase"></i> {{ optional($project->service)->title() }} &nbsp; <i class="fa-solid fa-location-dot"></i> {{ $project->location() }} &nbsp; <i class="fa-regular fa-calendar"></i> {{ optional($project->project_date ?: $project->finished_at ?: $project->started_at)->format('d/m/Y') }}</p></div>
  <div class="col-lg-4"><div class="card card-sj p-3"><b>{{ app()->getLocale()==='en'?'Photo Summary':'สรุปรูปภาพ' }}</b><div>{{ app()->getLocale()==='en'?'Total':'รูปทั้งหมด' }}: {{ $project->images->count() }}</div><div>{{ app()->getLocale()==='en'?'Before':'ก่อนทำ' }}: {{ $project->images->where('image_type','before')->count() }}</div><div>{{ app()->getLocale()==='en'?'Progress':'ระหว่างทำ' }}: {{ $project->images->where('image_type','progress')->count() }}</div><div>{{ app()->getLocale()==='en'?'After':'หลังทำ' }}: {{ $project->images->where('image_type','after')->count() }}</div></div></div>
 </div>
 <div class="card card-sj p-4 my-4">{!! nl2br(e($project->description())) !!}</div>
 @foreach(['before'=>app()->getLocale()==='en'?'Before':'ก่อนทำ','progress'=>app()->getLocale()==='en'?'Progress':'ระหว่างทำ','after'=>app()->getLocale()==='en'?'After':'หลังทำ'] as $type=>$label)
  <div class="mb-5"><h3 class="fw-bold mb-3">{{ $label }}</h3><div class="row g-3">@forelse($project->images->where('image_type',$type) as $img)<div class="col-6 col-md-4"><a href="{{ asset('storage/'.$img->image_path) }}" target="_blank"><img class="gallery-thumb shadow-sm" src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $img->caption() ?: $project->title() }}"></a>@if($img->caption())<div class="small text-muted mt-1">{{ $img->caption() }}</div>@endif</div>@empty<div class="col-md-4"><div class="project-ph">{{ strtoupper($type) }}</div></div>@endforelse</div></div>
 @endforeach
</div></section>
@endsection
